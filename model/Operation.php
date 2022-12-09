<?php

require_once "framework/Model.php";


class Operation extends Model {

    public function __construct(public string $title, public int $tricount,public int $amount,
                                 public ?string $operation_date=null, public int $initiator, public ?string $created_at=null  ){
      
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