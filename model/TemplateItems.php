<?php 

require_once "framework/Model.php";
require_once "model/Template.php";
require_once "model/User.php";

class TemplateItems extends Model {


    public function __construct(public User $user, public Template $template,public int $weight){
        
    }
}

?>