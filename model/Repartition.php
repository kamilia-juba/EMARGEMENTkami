<?php 

require_once "framework/Model.php";
require_once "model/Template.php";
require_once "model/User.php";

class Repartition extends Model {


    public function __construct(public Operation $operation,public User $user ,public int $weight){   
    }

    public static function get_repartition_by_operation (Operation $operation): array{ //recupere les template d'un tricount
        $query = self::execute("select * from repartitions where operation = :operationId ORDER BY user", ["operationId"=>$operation->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Repartition(Operation::get_operation_by_id($row["operation"]),User::get_user_by_id($row["user"]),$row["weight"]);
        }
        return $results;
    }
}

?>