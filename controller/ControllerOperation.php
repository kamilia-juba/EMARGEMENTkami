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
            $amounts = [];
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
                                        "user_participates" => $user_participates]
                                    );
        }else{
            $this->redirect("Main");
        }
    }
}
?>