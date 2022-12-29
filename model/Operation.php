<?php

require_once "framework/Model.php";
require_once "model/Tricount.php";


class Operation extends Model {

    public function __construct(public string $title, 
                                public int $tricount,
                                public float $amount,
                                public int $initiator,
                                public ?string $created_at=null,
                                public ?string $operation_date=null,
                                public ?int $id=null){
      
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

    public static function get_operation_by_id(int $id) : Operation{
        $query = self::execute("SELECT * FROM operations WHERE tricount = :tricountId",["tricountId"=>$id]);
        $data = $query->fetch();
        return new Operation($data["mail"],$data["hashed_password"],$data["full_name"],$data["role"],$data["iban"], $data["id"]);

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

    public function persist() : Operation {
        
        self::execute("INSERT INTO operations(title,tricount,amount,operation_date,initiator,created_at) VALUES(:title,:tricount,:amount,:operation_date,:initiator,:created_at)", 
                        [ "title"=>$this->title,
                        "tricount"=>$this->tricount,
                        "amount"=>$this->amount,
                        "operation_date"=>$this->operation_date,
                        "initiator"=>$this->initiator,
                        "created_at"=>$this->created_at]);
        $lastid= Model::lastInsertId();
        self::execute("INSERT INTO repartitions(operation,user,weight) VALUES(:operation,:user,:weight)", 
                        [ "weight"=>1,
                        "operation"=>$lastid,
                        "user"=>$this->initiator]);                
        return $this;
    }

    public function validate_title() : array {
        $errors = [];
        if (strlen($this->title) == 0) {
            $errors[] = "Title is mandatory";
        } if ((strlen($this->title) < 3)) {
            $errors[] = "Title must have at least 3 characters";
        }
        return $errors;
    }

    public function validate_amount() : array {
        $errors = [];
        if (($this->amount) <= 0 || ($this->amount)== "" ) {
            $errors[] = "Amount must be positive";
        }
        return $errors;
    }
}

?>