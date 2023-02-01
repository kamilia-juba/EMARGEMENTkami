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


    public static function get_total_weights(int $id): int{
        $query = self::execute("SELECT sum(weight) total from repartitions WHERE operation=:operationId",["operationId" => $id]);
        $data = $query->fetch();
        return $data["total"];
    }

    public function get_weight(int $userId): int | null {
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :operationId and user = :userId",["operationId" => $this->id, "userId" => $userId]);
        $data = $query->fetch();
        return $data === false ? null : $data["weight"];
    }

    public static function get_weight_from_template_static(User $participant, Template $template){ // a changer pour rendre l'autre static
        $query = self::execute("SELECT * FROM repartition_template_items WHERE user = :userId and repartition_template=:templateId", ["userId" => $participant->id, "templateId" => $template->id]);
        $data = $query->fetch();
        return $data === false ? null : $data["weight"];

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

    public function get_participants():array{
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :id",["id" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = $row["user"];
        }
        return $results;
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
        $this->id=$lastid;           
        return $this;
    }
    public static function validate_title(String $title): array {
        $errors = [];
        if(strlen($title)<=0) {
            $errors[] = "A title is required";
        }
        if(strlen($title)!=0 && strlen($title)<3){
            $errors[] = "Title must have at least 3 characters";
        }
        return $errors;
    }

    public static function validate_amount(float $amount): array {
        $errors = [];
        if($amount<=0){
            $errors[] = "Amount must be greater than 0";
        }
        return $errors;
    }

    public function updateOperation(){
        self::execute("UPDATE operations SET title=:title, amount=:amount, operation_date=:operation_date, initiator=:initiator WHERE id=:id",
                        ["id" => $this->id, 
                        "title" => $this->title, 
                        "amount" => $this->amount, 
                        "operation_date" => $this->operation_date,
                        "initiator" => $this->initiator]);
    }

    public function delete_repartitions(){
        self::execute("DELETE FROM repartitions WHERE operation=:operation",["operation" => $this->id]);
    }

    public function add_repartitions(User $user, int $weight){
        self::execute("INSERT INTO repartitions(operation,user,weight) VALUES(:operation,:user,:weight) ",
                     ["operation" => $this->id,
                      "user" => $user->id,
                      "weight" => $weight]);
    }

    public function delete_operation(){
        self::execute("delete from repartitions where operation=:operationId",["operationId" => $this->id]);
        self::execute("delete from operations where id=:operationId",["operationId" => $this->id]);
    }
}

?>