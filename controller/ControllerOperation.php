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

    public function editOperation(): void {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $success = "";
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $operation = Operation::get_operation_byid($_GET["param1"]);
            $tricount = Tricount::getTricountById($operation->tricount, $user->mail);
            $participants = $tricount->get_participants();
            $repartition_templates = $tricount->get_repartition_templates();

            if(isset($_POST["title"]) && isset($_POST["amount"])){
                $title = $_POST["title"];
                $amount = $_POST["amount"];
                $errors = array_merge($errors,$operation->validate_title($title));
                $errors = array_merge($errors,$operation->validate_amount($amount));
            }

            if(count($_POST) > 0 && count($errors)==0){
                $operation->title = $title;
                $operation->amount = $amount;
                $success = "Your operation has been successfully updated";
            }

            (new View("edit_operation"))->show(["operation" => $operation,"user"=>$user,"tricount" => $tricount,"participants" => $participants,"repartition_templates"=>$repartition_templates,"errors" => $errors,"success" => $success]);
        } else{
            $this->redirect("Main");
        }
    }
}
?>