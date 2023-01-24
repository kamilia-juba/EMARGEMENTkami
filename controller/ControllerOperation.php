<?php 
require_once 'model/Operation.php';
require_once 'controller/MyController.php';
require_once 'model/User.php';
require_once 'model/Tricount.php';

class ControllerOperation extends Mycontroller{

    public function index() : void {
    }

    public function showOperation(): void {
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $operation = Operation::get_operation_byid($_GET["param1"]);
            $tricount = Tricount::getTricountById($operation->tricount,$user->mail);
            $paidBy = User::get_user_by_id($operation->initiator);
            $participants = $operation->get_participants();
            $user_participates = false;
            $users = [];
            $operations = Operation::get_operations_by_tricountid($tricount->id);
            $currentIndex = 0;
            for($i=0;$i<sizeof($operations);++$i){
                if($operations[$i]->id == $operation->id){
                    $currentIndex = $i;
                }
            }
            $total_weight = Operation::get_total_weights($operation->id);
            foreach($participants as $participant){
                if($participant==$user->id){
                    $user_participates = true;
                }
                $weight = $operation->get_weight($participant);
                $users[] = [User::get_user_by_id($participant),round(($operation->amount/$total_weight)*$weight,2)];
            }
            (new View("operation"))->show(
                                        ["user" => $user, 
                                        "operation" => $operation, 
                                        "tricount" => $tricount, 
                                        "paidBy" => $paidBy, 
                                        "users" => $users ,
                                        "user_participates" => $user_participates,
                                        "currentIndex" => $currentIndex,
                                        "operations" => $operations]
                                    );
        }else{
            $this->redirect("Main");
        }
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


            $operation = new Operation($title, $tricount->id, $amount, $paidBy,date("Y-m-d H:i:s"), $date);
            $errors = array_merge($errors, $operation->validate_title());
            $errors = array_merge($errors, $operation->validate_amount());
            $errorsTitle = array_merge($errorsTitle, $operation->validate_title());
            $errorsAmount = array_merge($errorsAmount, $operation->validate_amount());


            if (count($errors) == 0) { 
                $operation->persist(); //sauve l'utilisateur
                $this->log_user($user);
            }
        }

        (new View("add_operation"))->show(["title" => $title, 'amount'=> $amount,'date'=> $date, 
        "errorsTitle" => $errorsTitle,"errorsAmount" => $errorsAmount, "tricount"=> $tricount, "datas" => $data]);
        }else{
            $this->redirect("main");
        }
    }
}
?>