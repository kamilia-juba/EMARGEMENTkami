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


    //méthode qui renvoie l'user qui a payé l'opération
    public function get_payer(): User{
        $query = self::execute("SELECT * from Users where id = (SELECT initiator FROM operations WHERE id=:id)",["id"=>$this->id]);
        $data = $query->fetch();
        return new User($data["mail"],$data["hashed_password"],$data["full_name"],$data["role"],$data["iban"], $data["id"]);
    }

    //méthode  qui récupère le poids total de l'opération
    public  function get_total_weights(): int{
        $query = self::execute("SELECT sum(weight) total from repartitions WHERE operation=:operationId",["operationId" => $this->id]);
        $data = $query->fetch();
        return $data["total"];
    }

    //méthode qui récupère le poids sur l'opération d'un user par rapport à son id donné en paramètre
    public function get_weight(User $user): int | null {
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :operationId and user = :userId",["operationId" => $this->id, "userId" => $user->id]);
        $data = $query->fetch();
        return $data === false ? null : $data["weight"];
    }

    //méthode qui détermine si un user participe à l'opération ou non
    public function user_participates(User $user):bool{
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :operationId",["operationId" => $this->id]);
        $data = $query->fetchAll();
        foreach($data as $row){
            if($row["user"]==$user->id){
                return true;
            }
        }
        return false;
    }
   
    //méthode statique qui récupère une opération par son id
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

    //méthode qui récupère tous les participants de l'opération
    public function get_participants():array{
        $query = self::execute("SELECT * FROM repartitions WHERE operation = :id",["id" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = $row["user"];
        }
        return $results;
    }

    //méthode qui insère dans la table operations dans la BDD une nouvelle opération et puis la renvoie
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

    //méthode qui met à jour une opération dans la BDD
    public function updateOperation(){
        self::execute("UPDATE operations SET title=:title, amount=:amount, operation_date=:operation_date, initiator=:initiator WHERE id=:id",
                        ["id" => $this->id, 
                        "title" => $this->title, 
                        "amount" => $this->amount, 
                        "operation_date" => $this->operation_date,
                        "initiator" => $this->initiator]);
    }

    //méthode qui supprime de la BDD les répartitions d'une opération
    public function delete_repartitions(){
        self::execute("DELETE FROM repartitions WHERE operation=:operation",["operation" => $this->id]);
    }

    //méthode qui ajoute dans la BDD les répartitions d'une opération
    public function add_repartitions(User $user, int $weight){
        self::execute("INSERT INTO repartitions(operation,user,weight) VALUES(:operation,:user,:weight) ",
                     ["operation" => $this->id,
                      "user" => $user->id,
                      "weight" => $weight]);
    }

    //méthode qui supprime une opération et tous ses liens dans la BDD
    public function delete_operation(){
        self::execute("delete from repartitions where operation=:operationId",["operationId" => $this->id]);
        self::execute("delete from operations where id=:operationId",["operationId" => $this->id]);
    }
}

?>