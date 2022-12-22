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

    public function get_tricount_by_id() : Tricount|false {
        $query = self::execute("SELECT * FROM tricounts where id = :id", ["id"=>$this->id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Tricount($data["title"], $data["created_at"], $data["creator"], $data["description"], $data["id"]);
        }
    }
    public function persist(int $id) : Tricount {
        $T = time();
        $D = date("y-m-d h:m:s", $T);
       
       if(self::get_tricount_by_id())
            self::execute("UPDATE tricounts SET   title=:title, description=:description 
                           WHERE id=:id ", 
                            [ 
                                "title"=>$this->title,
                                "description"=>$this->description
                              
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
        $errors =[];
        if(strlen($this->title)==0){
            $errors[] = 'title cant be empty';
        }
        return $errors;

    }
    



   
}

?>