<?php

require_once "framework/Model.php";


class Operation extends Model {

    public function __construct(public string $title, public int $tricount,public int $amount,
                                 public ?string $operation_date=null, public int $initiator, public ?string $created_at=null  ){
      
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

    public static function get_operations_by_tricountid(Tricount $tricount) : array{

        $query = self::execute("select * from operations where tricount = :tricountId order by created_at DESC", ["tricounId"=> $tricount->id]);
        $data = $query->fetchAll();
        $operations = [];
        foreach ($data as $row) {
            $operations[] = new Operation(($row['title']),($row['tricount']), $row['amount'], $row['operation_date'], $row['initiator'], $row['created_at']);
        }
        return $operations;

    }
}