<?php

require_once "framework/Model.php";
require_once "model/Tricount.php";


class Operation extends Model {

    public function __construct(public string $title, public int $tricount,public float $amount,
                                  public int $initiator, public ?string $created_at=null,public ?string $operation_date=null, public ?int $id=null){
      
    }

    public function validate() : array {
        $errors = [];
        date_default_timezone_set('UTC');
        $today = date("Y-m-d H:i:s");

    
        if(($this->operation_date)>$this->$today){
            $errors[] = "operation_date cannot be greater than the current date";
        }

        if(strlen($this->title)==0){
            $errors[] = "Body must be filled";
        }
    
        if((($this->amount) <0)){
            $errors[] = "the amount must be greater than zero";
        }
        return $errors;
    }

    public static function get_operations_by_tricountid(int $tricountId) : array{

        $query = self::execute("SELECT * FROM operations WHERE tricount = :tricountId order by created_at DESC", ["tricountId" => $tricountId]);
        $data = $query->fetchAll();
        $operations = [];
        foreach ($data as $row) {
            $operations[] = new Operation($row['title'],$row['tricount'], round($row['amount'],2), $row['initiator'], $row['created_at'], $row['operation_date'],$row['id']);
        }
        return $operations;

    }

    public function get_payer(): User{
        $query = self::execute("SELECT * from Users where id = (SELECT initiator FROM operations WHERE id=:id)",["id"=>$this->id]);
        $data = $query->fetch();
        return new User($data["mail"],$data["hashed_password"],$data["full_name"],$data["role"],$data["iban"], $data["id"]);
    }

    public static function get_total_weights(int $id): int{
        $query = self::execute("SELECT sum(weight) total from repartitions WHERE operation=:operationId",["operationId" => $id]);
        $data = $query->fetch();
        return $data["total"];
    }

    public function get_weight(int $userId): int {
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :operationId and user = :userId",["operationId" => $this->id, "userId" => $userId]);
        $data = $query->fetch();
        return $data["weight"];
    }

    public function user_participates(int $userId):bool{
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :operationId",["operationId" => $this->id]);
        $data = $query->fetchAll();
        foreach($data as $row){
            if($row["user"]==$userId){
                return true;
            }
        }
        return false;
    }

    public static function get_operation_byid(int $id):Operation{
        $query = self::execute("SELECT * FROM operations WHERE id = :id",["id"=>$id]);
        $data = $query->fetch();
        return new Operation(
            $data["title"],
            $data["tricount"],
            round($data["amount"],2),
            $data["initiator"],
            $data["created_at"],
            $data["operation_date"],
            $data["id"]
        );
    }
}

?>