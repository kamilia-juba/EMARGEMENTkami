<?php 

require_once "framework/Model.php";
require_once "model/Template.php";
require_once "model/User.php";

class TemplateItems extends Model {


    public function __construct(int $weight, User $user, Template $template){
        
    }

    public static function get_template_items():array{
        $query = self::execute("SELECT * FROM repartition_template_items",[]);
        $data = $query->fetchAll();

        $res = [];

        foreach($data as $row){
            $user = User::get_user_by_id($row["user"]);
            $template = Template::get_template_by_id($row["repartition_template"]);
            $res[] = new TemplateItems($row["weight"], $user, $template);
        }

        return $res;
    }
}

?>