<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';
require_once 'model/Operation.php';


class ControllerOperation extends MyController{
    public function index() : void {
    }
    
    public function add_operation() : void {
        $user = $this->get_user_or_redirect();
        
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $tricount = Tricount::getTricountById($_GET["param1"], $user->mail);
        $title = "";
        $amount = "";
        $date = "";
        $paidBy = "";
        $errorsTitle = [];
        $errorsAmount = [];
        $data= $tricount->get_all_tricount_participants();
        $errors= [];

        if (isset($_POST['title']) && isset($_POST['amount']) && isset($_POST['date']) && 
        isset($_POST['paidBy'])) {
       
            $title = trim($_POST['title']);
            $amount = floatval(trim($_POST['amount']));
            $date = trim($_POST['date']);
            $paidBy = trim($_POST['paidBy']);


            $operation = new Operation($title, $tricount->id, $amount, $paidBy,date("Y-m-d"), $date);
            $errors = array_merge($errors, $operation->validate_title());
            $errors = array_merge($errors, $operation->validate_amount());
            $errorsTitle = array_merge($errorsTitle, $operation->validate_title());
            $errorsAmount = array_merge($errorsAmount, $operation->validate_amount());


            if (count($errors) == 0) { 
                $operation->persist(); //sauve l'utilisateur
                $this->log_user($user);
                (new View("add_operation"))->show(["title" => $title, 'amount'=> $amount,'date'=> $date, 
                "errorsTitle" => $errorsTitle,"errorsAmount" => $errorsAmount, "tricount"=> $tricount, "datas" => $data]);
            }
        }

        (new View("add_operation"))->show(["title" => $title, 'amount'=> $amount,'date'=> $date, 
        "errorsTitle" => $errorsTitle,"errorsAmount" => $errorsAmount, "tricount"=> $tricount, "datas" => $data]);
        }  
    }
}

?>