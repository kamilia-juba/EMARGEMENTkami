<?php 

    require_once "framework/Model.php";


    class Template extends Model{

        public function __construct (public int $id, public string $title, public int $tricount ){

        }

        public function get_templates_by_tricount(Tricount $tricount): array{
            $query = self::execute("select * from repartition_templates where tricount = :tricountId", ["tricountId"=>$tricount->$id]);
            $data = $query->fetchAll();
            $results = [];
            foreach($data as $row){
                $results[] = new Template($row["id"],$row["title"],$row["tricount"]);
            }
            return $results;
        }

    }

?>