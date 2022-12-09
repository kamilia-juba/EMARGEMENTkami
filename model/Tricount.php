<?php 

require_once "framework/Model.php";

class Tricount extends Model{

    public function __construct(public ?int $id = null, public string $title, public ?string $description = null, public string $created_at, public int $creator ){
        
    }

    public static function get_user_tricounts(User $user) : array {
        $query = self::execute("select * from tricounts where creator = :userId", ["userId"=>$user->$id]);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row){
            $results[] = new Tricount($row["id"], $row["title"], $row["description"], $row["created_at"], $row["creator"] );
        }

        return $results;
    }
}

?>