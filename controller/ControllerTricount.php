<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';



class ControllerTricount extends MyController{

    public function index() : void {
        if ($this->user_logged()) {
            $user = $this->get_user_or_redirect();
            $tricounts = $user->get_user_tricounts();
            (new View("listTricounts"))->show(["tricounts" => $tricounts]);
        } else {
            $this->redirect("Main");
        }
    }

}



?>