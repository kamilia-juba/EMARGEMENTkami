<?php 

require_once "framework/Model.php";
require_once "model/Operation.php";
require_once "model/Template.php";

class Tricount extends Model{

    public function __construct(public string $title, public string $created_at, public int $creator, public ?string $description, public ?int $id=null){
        
    }

    public function nbParticipantsTricount(): int {
        $query = self::execute("select count(*) from subscriptions where tricount = :tricountID", ["tricountID" => $this->id]);
        $data = $query->fetch();
        return $data[0]-1;
    }

    public function get_tricount_by_id() : Tricount|false {
        $query = self::execute("SELECT * FROM tricounts where id = :id", ["id"=>$this->id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Tricount($data["title"], $data["created_at"], $data["creator"], $data["description"], $data["id"]);
        }
    }

    public function persist(int $id) : Tricount {
        $T = time();
        $D = date("y-m-d h:m:s", $T);
       
       if(self::get_tricount_by_id())
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

    public  function  valide_title( ) : array{
        $errors =[];
        if(strlen($this->title)==0){
            $errors[] = 'title cant be empty';
        }
        return $errors;

    }

    public static function getTricountById(int $id): Tricount{
        $query = self::execute("SELECT * FROM tricounts WHERE id = :id", ["id"=>$id]);
        $data = $query->fetch();
        return new Tricount($data["title"],$data["created_at"],$data["creator"],$data["description"],$data["id"]);
   }

   public function get_logged_user_total(int $id): float|null {
        $query = self::execute("SELECT sum(amount) total from operations where initiator=:id and operations.tricount=:tricountId", ["id"=>$id,"tricountId"=>$this->id]);
        $data = $query->fetch();
        if($data["total"]==null || $data["total"]==0){
            return 0;
        }
        return round($data["total"],2);
    }

    public function get_total_expenses(): float|null{
        $query = self::execute("SELECT sum(amount) total FROM operations where tricount = :tricountId",["tricountId"=>$this->id]);
        $data = $query->fetch();
        if($data["total"]==null || $data["total"]==0){
            return 0;
        }
        return round($data["total"],2);
    }

    public function get_all_tricount_participants() : array{
        $query = self::execute("SELECT * from users where id in (select user from subscriptions where tricount = :tricountId)",["tricountId"=>$this->id]);
        $data= $query->fetchAll();
        $results = [];

        foreach ($data as $row) {
            $user = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"], $row["id"]);
            $results[]=$user;
        }
        return $results;
    }


    public function get_balances(int $tricountID):array{
        $operations=[];
        $participant=[];
    
        $operations = Operation::get_operations_by_tricountid($tricountID);
        $participants = $this->get_participants();


        foreach($operations as $operation){

            $totalWeight=Operation::get_total_weights($operation->id);
            $payer=$operation->get_payer();
            $sum=$operation->amount;

            $individualAmout= $sum/$totalWeight;

            foreach($participants as $participant){
                if($operation->user_participates($participant->id)){
                    $participantId=$participant->id;
                    $payerID=$payer->id;
                    $myWeight=$operation->get_weight($participantId);
                    if($payerID==$participantId){
                        $participant->account+=$sum-($myWeight*$individualAmout);
                    }
                    else{
                        $participant->account-=$myWeight*$individualAmout;
                    }
                }
            }
        }
        return $participants;
    }

    public function get_participants():array{
        $query = self::execute("SELECT * FROM users WHERE id in (SELECT DISTINCT user FROM subscriptions WHERE tricount=:id)",["id" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new User($row["mail"],$row["hashed_password"],$row["full_name"],$row["role"],$row["iban"],$row["id"]);
        }
        return $results;
    }

    public function get_repartition_templates():array{
        $query = self::execute("SELECT * FROM repartition_templates WHERE tricount = :tricountId",["tricountId" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Template($row["title"],$row["tricount"],$row["id"]);
        }
        return $results;
    }

    public function template_name_exists(string $title): bool{
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

    public function add_template(string $title): Template{
        self::execute("INSERT INTO repartition_templates(title,tricount) VALUES (:title, :tricount)",
                        ["title" => $title, 
                        "tricount" => $this->id]
        );
        return new Template($title,$this->id,Model::lastInsertId());
    }

    public function persistUpdate(){
        self::execute("UPDATE tricounts SET title=:title, description=:description where id=:id",
    
                     ["id"=> $this->id,
                    "title"=>$this->title,
                    "description"=>$this->description,
                ]);
    
    }

    public function add_subscriber(int $userId){
        self::execute("INSERT INTO subscriptions VALUES (:tricountId,:userId)",
        ["tricountId" => $this->id, 
        "userId" =>$userId]);
    }

    public function delete_participations(int $userID):void{
        self::execute("delete from subscriptions where tricount=:tricountID and user=:userID",["tricountID" => $this->id,"userID"=>$userID]);
    }

    public function delete_repartition_templates():void{
        $this->delete_repartition_template_items();
        self::execute("delete from repartition_templates where tricount=:tricountID",["tricountID" => $this->id]);
    }

    public function delete_repartition_template_items():void{
        self::execute("delete from repartition_template_items where repartition_template in (select id from repartition_templates where tricount=:tricountID)",["tricountID" => $this->id]);
    }


    public function delete_tricount(int $userID):void{
        $operations=Operation::get_operations_by_tricountid($this->id);
        foreach($operations as $operation){
            $operation->delete_operation();
        }
        $this->delete_participations($userID);
        $this->delete_repartition_templates();
        self::execute("delete from tricounts where id=:tricountID",["tricountID" => $this->id]);
    }

}

?>