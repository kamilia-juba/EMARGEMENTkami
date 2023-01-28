<?php 
require_once 'model/Operation.php';
require_once 'controller/MyController.php';
require_once 'model/User.php';
require_once 'model/Tricount.php';

class ControllerTemplate extends Mycontroller{

    public function index() : void {
    }

    public function deleteTemplate(){
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] != "" && isset($_GET["param2"]) && $_GET["param2"] != "" && $user->isSubscribedToTricount($_GET["param1"])){
            $tricount = Tricount::getTricountById($_GET["param1"],$user->mail);
            $template = Template::get_template_by_id($_GET["param2"]);
            if(isset($_POST["yes"])){
                $template->remove_template();
                //a ajouter redirection vers edit_template
            }else if(isset($_POST["no"])){
                //a ajouter redirection vers edit_template
            }

            (new View("delete_template"))->show(
                                                ["tricount" => $tricount,
                                                "template" => $template]
                                            );

        }else{
            $this->redirect("Main");
        }
    }

}

?>