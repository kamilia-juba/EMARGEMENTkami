<?php 

    require_once "framework/Model.php";


    class Template extends Model{

        public function __construct (public string $title, public int $tricount, public ?int $id=null){

        }

        public function get_templates_by_tricount(Tricount $tricount): array{
            $query = self::execute("select * from repartition_templates where tricount = :tricountId", ["tricountId"=>$tricount->id]);
            $data = $query->fetchAll();
            $results = [];
            foreach($data as $row){
                $results[] = new Template($row["id"],$row["title"],$row["tricount"]);
            }
            return $results;
        }

        public static function get_template_by_id(int $id): Template{
            $query = self::execute("SELECT * FROM repartition_templates WHERE id=:id", ["id" => $id]);
            $data = $query->fetch();
            return new Template($data["title"], $data["tricount"], $data["id"]);
        }

    }

?>