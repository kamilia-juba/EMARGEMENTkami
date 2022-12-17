<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';
require_once 'model/Operation.php';


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
                $tricount = Tricount::getTricountById($_GET["param1"], $user->mail);
                $operations = Operation::get_operations_by_tricountid($tricount->id);
            }
            (new View("tricount"))->show(["tricount" => $tricount, "operations" => $operations,"user"=>$user]);
        }else{
            $this->redirect("Main");
        }
    }

}



?>