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

        public function persist(int $weight, Operation $operation, User $user){
            $query = self::execute("UPDATE repartitions SET weight=:weight WHERE operation=:operation and user=:user ",
                                    ["weight" => $weight, 
                                    "operation" => $operation->id,
                                    "user" => $user->id]);
        }

        public function add_items(User $user, int $weight){
            self::execute("INSERT INTO repartition_template_items(user,repartition_template,weight) VALUES (:user,:repartition_template,:weight)",
                            ["user" => $user->id,
                            "repartition_template" => $this->id,
                            "weight" => $weight]
            );
        }

        private function remove_items(){
            self::execute("DELETE FROM repartition_template_items WHERE repartition_template = :id", ["id"=>$this->id]);
        }

        private function remove_repartition_template(){
            self::execute("DELETE FROM repartition_templates WHERE id=:id",["id" => $this->id]);
        }

        public function remove_template(){
            self::remove_items();
            self::remove_repartition_template();
        }

        public function get_repartition_template_users(): array{
            $query = self::execute("SELECT * FROM repartition_template_items WHERE repartition_template=:id", ["id" => $this->id]);
            $data = $query->fetchAll();
            $results = [];
            foreach($data as $row){
                $results[] = User::get_user_by_id($row["user"]);
            }
            return $results;
        }

        public function get_repartition_user_weight(int $userId):int{
            $query = self::execute("SELECT * FROM repartition_template_items WHERE user=:userId AND repartition_template=:templateId", ["userId" => $userId, "templateId" => $this->id]);
            $data = $query->fetch();
            return $data["weight"];
        }

        public function get_repartition_total_weight(): int{
            $query = self::execute("SELECT sum(weight) total FROM repartition_template_items WHERE repartition_template=:id", ["id" => $this->id]);
            $data = $query->fetch();
            return $data["total"];
        }
    }
?>