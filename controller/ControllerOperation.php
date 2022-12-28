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
            foreach($participants as $participant){
                if($participant==$user->id){
                    $user_participates = true;
                }
            }
            (new View("operation"))->show(["user" => $user, "operation" => $operation, "tricount" => $tricount, "paidBy" => $paidBy, "participants" => $participants ,"user_participates" => $user_participates]);
        }else{
            $this->redirect("Main");
        }
    }
}
?>