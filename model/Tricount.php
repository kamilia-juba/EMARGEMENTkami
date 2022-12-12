<?php 

require_once "framework/Model.php";

class Tricount extends Model{

    public function __construct(public ?int $id = null, public string $title, public ?string $description = null, public string $created_at, public int $creator ){
        
    }

    public function nbParticipantsTricount(): int {
        $query = self::execute("select count(*) from participations where tricount = :tricountID", ["tricountID",$this->id]);
        $data = $query->fetch();
        return $data -1;
    }
}

?>