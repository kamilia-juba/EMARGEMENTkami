<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';



class ControllerTricount extends MyController{

    public function index() : void {
    }

    public function yourTricounts(): void {
        if ($this->user_logged()) {
            $user = $this->get_user_or_redirect();
            $tricounts = $user->get_user_tricounts();
            (new View("listTricounts"))->show(["tricounts" => $tricounts]);
        } else {
            $this->redirect("Main");
        }
    }

    public function showTricount(): void{
        if($this->user_logged()){
            $user = $this->get_user_or_redirect();
            if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
                $tricount = Tricount::getTricountByTitle($_GET["param1"], $user->mail);
            }
            (new View("tricount"))->show(["tricount" => $tricount]);
        }else{
            $this->redirect("Main");
        }
    }

}



?>