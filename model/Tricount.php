<?php 

require_once "framework/Model.php";

class Tricount extends Model{

    public function __construct(public string $title, public string $created_at, public int $creator, public int $id, public ?string $description){
        
    }

    public function nbParticipantsTricount(): int {
        $query = self::execute("select count(*) from subscriptions where tricount = :tricountID", ["tricountID" => $this->id]);
        $data = $query->fetch();
        return $data[0];
    }
}

?>