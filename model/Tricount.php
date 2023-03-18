<?php 

require_once "framework/Model.php";
require_once "model/Operation.php";
require_once "model/Template.php";

class Tricount extends Model{

    public function __construct(public string $title, public string $created_at, public int $creator, public ?string $description, public ?int $id=null){
        
    }

    public function nb_participants_tricount(): int { // recuperation du nb de participants d'un tricount
        $query = self::execute("select count(*) from subscriptions where tricount = :tricountID", ["tricountID" => $this->id]);
        $data = $query->fetch();
        return $data[0]-1;
    }

    public function persist(int $id) : Tricount { //sauvegarde le tricount
        $T = time();
        $D = date("y-m-d h:m:s", $T);
       
       if(self::get_tricount_by_id($id)) // si il existe déjà update sinon le sauve
            self::execute("UPDATE tricounts SET   title=:title, description=:description 
                           WHERE id=:id ", 
                            [ 
                                "title"=>$this->title,
                                "description"=>$this->description
                              
                               ]);
        else
            self::execute("INSERT INTO tricounts(title,description,created_at,creator) VALUES(:title,:description,:created_at,:creator)", 
                            [ 
                                "title"=>$this->title,
                                "description"=>$this->description,
                                "created_at"=>$D,
                                "creator"=>$id]);
        return $this;
    }

    public static function get_tricount_by_id(int $id): Tricount{ // recupere un tricount depuis une id passée en paramètre
        $query = self::execute("SELECT * FROM tricounts WHERE id = :id", ["id"=>$id]);
        $data = $query->fetch();
        return new Tricount($data["title"],$data["created_at"],$data["creator"],$data["description"],$data["id"]);
   }

   public function get_logged_user_total(int $id): float|null { // sert a recuperer le total d'un user
        $query = self::execute("SELECT sum(amount) total from operations where initiator=:id and operations.tricount=:tricountId", ["id"=>$id,"tricountId"=>$this->id]);
        $data = $query->fetch();
        if($data["total"]==null || $data["total"]==0){
            return 0;
        }
        return round($data["total"],2);
    }

    public function get_total_expenses(): float|null{ //recupere le total d'une operation
        $query = self::execute("SELECT sum(amount) total FROM operations where tricount = :tricountId",["tricountId"=>$this->id]);
        $data = $query->fetch();
        if($data["total"]==null || $data["total"]==0){
            return 0;
        }
        return round($data["total"],2);
    }

    
    //méthode statique qui récupère toutes les opérations d'un tricount par rapport à un id donné en paramètre
    public  function get_operations() : array{

        $query = self::execute("SELECT * FROM operations WHERE tricount = :tricountId order by operation_date DESC, id DESC", ["tricountId" => $this->id]);
        $data = $query->fetchAll();
        $operations = [];
        foreach ($data as $row) {
            $operations[] = new Operation($row['title'],$row['tricount'], round($row['amount'],2), $row['initiator'], $row['created_at'], $row['operation_date'],$row['id']);
        }
        return $operations;

    }

    public function get_all_tricount_participants() : array{    // recupere tout les participants d'un tricount
        $query = self::execute("SELECT * from users where id in (select user from subscriptions where tricount = :tricountId)",["tricountId"=>$this->id]);
        $data= $query->fetchAll();
        $results = [];

        foreach ($data as $row) {
            $user = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"], $row["id"]);
            $results[]=$user;
        }
        return $results;
    }


    public function get_balances():array{   // return un tableau de participants avec chacun leurs balance
        $operations=[];
        $participant=[];
    
        $operations = $this->get_operations(); //recupartion de toute les operation d'un tricount
        $participants = $this->get_participants();  // recuperation de tout les participatns d'un tricount


        foreach($operations as $operation){

            $totalWeight=$operation->get_total_weights();      // pour chaque operation recupere le poids total ainsi que le celui qui a payé
            $payer=$operation->get_payer();
            $sum=$operation->amount;    // recuperation du total afin de calculé le montant d'une seule part 

            $individualAmout= $sum/$totalWeight; //calcul d'une part

            foreach($participants as $participant){
                if($operation->user_participates($participant)){ //si le user participe alors recupere son id ainsi que celui qui a payé l'operation
                    $participantId=$participant->id;
                    $payerID=$payer->id;
                    $myWeight=$operation->get_weight(User::get_user_by_id($participantId));
                    if($payerID==$participantId){                   // si le user en question a payé alors
                        $participant->account+=$sum-($myWeight*$individualAmout); //ajout de la somme moins son poids multiplié par une part
                    }
                    else{                                                  // sinon
                        $participant->account-=$myWeight*$individualAmout; //diminue de son compte le poids multiplié par une part
                    }
                }
            }
        }
        return $participants;
    }

    public function get_my_total(User $user):float{ // recupere le total d'un user
        $operations=[];
        $participant=[];
        $res=0;
    
        $operations =  $this->get_operations();
        $participants = $this->get_participants();


        foreach($operations as $operation){

            $totalWeight=$operation->get_total_weights();  
            $sum=$operation->amount;
            $myWeight=$operation->get_weight($user);
            $individualAmout= $sum/$totalWeight;

            foreach($participants as $participant){
                if($operation->user_participates($participant)){
                    if($participant==$user){
                        $res+=$individualAmout*$myWeight;
                    }
                }
            }
        }
        return $res;
    }

    public function get_participants():array{ // recupere les participant d'un tricount
        $query = self::execute("SELECT * FROM users WHERE id in (SELECT DISTINCT user FROM subscriptions WHERE tricount=:id) ORDER BY full_name",["id" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $results;
    }

    public function get_repartition_templates():array{  // recupere les template du tricount
        $query = self::execute("SELECT * FROM repartition_templates WHERE tricount = :tricountId",["tricountId" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Template($row["title"],$row["tricount"],$row["id"]);
        }
        return $results;
    }

    public function template_name_exists(string $title): bool{ //verifie si un nom de template existe
        $query = self::execute("SELECT * FROM repartition_templates WHERE title=:title and tricount=:tricount",
                        ["title" => $title,
                        "tricount" => $this->id]
        );
        $data = $query->fetch();
        if(empty($data)){
            return false;
        }
        return true;
    }

    public function add_template(string $title): Template{ // ajoute un template en bdd
        self::execute("INSERT INTO repartition_templates(title,tricount) VALUES (:title, :tricount)",
                        ["title" => $title, 
                        "tricount" => $this->id]
        );
        return new Template($title,$this->id,Model::lastInsertId());
    }

    public function persist_update(){ // update un tricount en bdd
        self::execute("UPDATE tricounts SET title=:title, description=:description where id=:id",
    
                     ["id"=> $this->id,
                    "title"=>$this->title,
                    "description"=>$this->description,
                ]);
    
    }

    public function add_subscriber(User $user){ // ajoute un sub au tricount
        self::execute("INSERT INTO subscriptions VALUES (:tricountId,:userId)",
        ["tricountId" => $this->id, 
        "userId" =>$user->id]);
    }



    public function delete_repartition_templates():void{ // supprime en cascade n template
        $this->delete_repartition_template_items();
        self::execute("delete from repartition_templates where tricount=:tricountID",["tricountID" => $this->id]);
    }

    public function delete_repartition_template_items():void{// supprime les items repartition afin de l'utilisé dans la fonction delete repartition templates
        self::execute("delete from repartition_template_items where repartition_template in (select id from repartition_templates where tricount=:tricountID)",["tricountID" => $this->id]);
    }


    public function delete_tricount(User $user):void{ // supprime un tricount
        $operations= $this->get_operations();
        $participants=$this->get_all_tricount_participants();
        foreach($operations as $operation){
            $operation->delete_operation();
        }
        foreach($participants as $participant){
            $this->delete_participation($participant->id);
        }

        $this->delete_repartition_templates();
        self::execute("delete from tricounts where id=:tricountID",["tricountID" => $this->id]);
    }

    public function delete_participation(int $userID):void{ // supprime une participation d'un user a un tricount
        self::execute("delete from subscriptions where tricount =:tricountID and user=:userID",["tricountID" => $this->id,"userID"=>$userID]);
    }

    public function has_already_paid(User $user):bool{ // verifie si un user a deja participé une fois sur un tricount 
        $operations= $this->get_operations();
        $result=false;

        foreach($operations as $operation){
            if($operation->user_participates($user)){
                return true;
            }
        }
        return $result;
    }

    //returns true if there's already a combination of a title and a user given as parameters
    public static function tricount_title_already_exists(string $title, User $user){
        $query = self::execute("SELECT * FROM tricounts WHERE title=:title and creator=:user", ["title" => $title, "user" => $user->id]);
        $data = $query->fetch();
        return !empty($data);
    }

    
    public function get_operations_as_json() : string {
        $operations = $this->get_operations();

        $table = [];

        foreach ($operations as $operation) {

                $row = [];
                $row["id"] = $operation->id;
                $row["title"] = $operation->title;
                $row["tricount"] = $operation->tricount;
                $row["amount"] = $operation->amount;
                $row["operation_date"] = $operation->operation_date;
                $row["initiator"] = $operation->initiator;
                $row["created_at"] = $operation->created_at;
                $table[] = $row;
        }
        return json_encode($table);
    }
}

?>