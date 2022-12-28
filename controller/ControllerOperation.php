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
        $errors[] = "";
        $data[]= $tricount->get_all_tricount_participants();

        if (isset($_POST['title']) && isset($_POST['amount']) && isset($_POST['date']) && 
        isset($_POST['paidBy'])) {
       
            $title = trim($_POST['title']);
            $amount = trim($_POST['amount']);
            $date = trim($_POST['date']);
            $paidBy = $_POST['paidBy'];

            $operation = new Operation($title, $tricount->id, $amount, $paidBy, date("d/m/y","Europe/Brussels"), $date);
            /*$errors = User::validate_unicity($mail);
            $errors = array_merge($errors, $user->validate_full_name());
            $errors = array_merge($errors, $user->validate_mail($mail));
            $errors = array_merge($errors, $user->validate_IBAN($IBAN));
            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));

            if (count($errors) == 0) { 
                $user->persist(); //sauve l'utilisateur
                $this->log_user($user);
            }*/
            $operation -> persist();
            

        }

        (new View("add_operation"))->show(["title" => $title, 'amount'=> $amount,'date'=> $date, 
        "errors" => $errors, "tricount"=> $tricount, "data" => $data]);
        }  
    }
}

?>