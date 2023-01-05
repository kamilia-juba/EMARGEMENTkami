<?php 

require_once "framework/Model.php";
require_once "model/Operation.php";

class Tricount extends Model{

    public function __construct(public string $title, public string $created_at, public int $creator, public ?string $description, public ?int $id=null){
        
    }

    public function nbParticipantsTricount(): int {
        $query = self::execute("select count(*) from subscriptions where tricount = :tricountID", ["tricountID" => $this->id]);
        $data = $query->fetch();
        return $data[0];
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

   public static function getTricountById(int $id, String $mail): Tricount{
        $query = self::execute("SELECT * FROM tricounts WHERE id = :id and creator = (SELECT id from users WHERE mail = :mail)", ["id"=>$id, "mail"=>$mail]);
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

    public function get_totals(): array{
        $query = self::execute("SELECT initiator FROM operations where tricount = :tricountId GROUP BY initiator",["tricountId"=>$this->id]);
        $data=$query->fetchAll();
        $operations = Operation::get_operations_by_tricountid($this->id);
        $results = [];
        foreach($data as $row){
            $results[] = [$this->get_balance((int)$row[0],$operations),(int)$row[0]];
        }
        return $results;
    }


    private function get_balance(int $userId,array $operations):float{
        $total = 0;
        foreach ($operations as $operation){
            if($operation->initiator==$userId){
                $total+=$operation->amount;
            }else{
                if($operation->user_participates($userId)){
                    $weights = Operation::get_total_weights($operation->id);
                    $weight = $operation->get_weight($userId);
                    $total = $total - (($operation->amount/$weights)*$weight);
                } 
            }
        }
        return round($total,2);
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
}

?>