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

        public function remove_items(){
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

        public function user_participates(int $userId):bool{
            $query = self::execute("SELECT * FROM repartition_template_items WHERE repartition_template = :templateId",["templateId" => $this->id]);
            $data = $query->fetchAll();
            foreach($data as $row){
                if($row["user"]==$userId){
                    return true;
                }
            }
            return false;
        }

        public function template_name_exists(string $title): bool{
            $query = self::execute("SELECT * FROM repartition_templates WHERE title=:title and id!=:templateId and tricount=:tricount",
                            ["title" => $title,
                            "templateId" => $this->id,
                            "tricount" => $this->tricount]
            );
            $data = $query->fetch();
            if(empty($data)){
                return false;
            }
            return true;
        }
        
        public function add_template(string $title): Template{
        self::execute("INSERT INTO repartition_templates(title,tricount) VALUES (:title, :tricount)",
                        ["title" => $title, 
                        "tricount" => $this->id]
        );
        return new Template($title,$this->id,Model::lastInsertId());
        }

        public static function add_repartition_template(string $title, int $tricountId): Template{
            self::execute("INSERT INTO repartition_templates(title,tricount) VALUES(:title,:tricount)", ["title" => $title, "tricount" => $tricountId]);
            $lastId = Model::lastInsertId();
            return Template::get_template_by_id($lastId);
        }

        public static function validate_title(String $title): array {
            $errors = [];
            if(strlen($title)<=0) {
                $errors[] = "A title is required";
            }
            if(strlen($title)!=0 && strlen($title)<3){
                $errors[] = "Title must have at least 3 characters";
            }
            return $errors;
        }

        public function update_template(string $title){
            self::execute("UPDATE repartition_templates SET title=:title WHERE id=:id", ["title" => $title, "id" => $this->id]);
        }

        public static function get_weight_from_template(User $participant, Template $template){
            $query = self::execute("SELECT * FROM repartition_template_items WHERE user = :userId and repartition_template=:templateId", ["userId" => $participant->id, "templateId" => $template->id]);
            $data = $query->fetch();
            return $data === false ? null : $data["weight"];
        }
    }

    
?>