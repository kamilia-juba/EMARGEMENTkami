<?php 

require_once "framework/Model.php";

class Tricount extends Model{

    public function __construct(public string $title, public string $created_at, public int $creator, public ?string $description, public ?int $id=null){
        
    }

    public function nbParticipantsTricount(): int {
        $query = self::execute("select count(*) from subscriptions where tricount = :tricountID", ["tricountID" => $this->id]);
        $data = $query->fetch();
        return $data[0];
    }

    public static function get_tricount_by_id(int $id) : Tricount|false {
        $query = self::execute("SELECT * FROM Tricount where id = :id", ["id"=>$id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }
    public function persist(int $id) : Tricount {
        $T = time((date_default_timezone_get()));
        $D = date("y-m-d h:m:s", $T);
       
        if(self::get_tricount_by_id($this->id))
            self::execute("UPDATE tricounts SET   title=:title, description=:description 
                           WHERE id=:id ", 
                            [ 
                                "title"=>$this->title,
                                "id"=>$id,
                                "description"=>$this->description,
                                "created_at"=>$this->created_at,
                               ]);
        else
            self::execute("INSERT INTO tricounts(title,description,created_at,creator) VALUES(:title,:description,:created_at,:creator)", 
                            [ 
                                "title"=>$this->title,
                                "description"=>$this->description,
                                "created_at"=>$D,
                                "creator"=>$id]);
        return $this;
    }

    public  function  valide_title( ) : array{
        $errors[]='';
        if(strlen($this->title)==0){
            $errors = 'title cant be empty';
        }
        return $errors;

    }
    



   
}

?>