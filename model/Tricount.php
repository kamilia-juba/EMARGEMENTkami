<?php 

require_once "framework/Model.php";

class Tricount extends Model{

    public function __construct(public string $title, public string $created_at, public int $creator, public int $id, public ?string $description){
        
    }

    public function nbParticipantsTricount(): int {
        $query = self::execute("select count(*) from subscriptions where tricount = :tricountID", ["tricountID" => $this->id]);
        $data = $query->fetch();
        return $data[0];

   /* public static function get_tricount_by_id(int $id) : Tricount|false {
        $query = self::execute("SELECT * FROM Tricount where id = :id", ["id"=>$id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }
    public function persist() : Tricount {
        if(self::get_tricount_by_id($this->id))
            self::execute("UPDATE Tricount SET  id=:id, title=:title, description=:description, created_at=:created_at,
                         creator=:creator , WHERE id=:id ", 
                            [ "id"=>$this->id,
                                "title"=>$this->title,
                                "description"=>$this->description,
                                "created_at"=>$this->created_at,
                                "creator"=>$this->creator]);
        else
            self::execute("INSERT INTO Users(id,title,description,created_at,creator) VALUES(:id,:title,:description,:created_at,:creator)", 
                            [ "id"=>$this->id,
                                "title"=>$this->title,
                                "description"=>$this->description,
                                "created_at"=>$this->created_at,
                                "creator"=>$this->creator]);
        return $this;
    }

    public  function  valide_title( ) : array{
        $errors[]='';
        if(strlen($this->title)==0){
            $errors = 'title cant be empty';
        }
        return $errors;

    }
    */



   }
}

?>