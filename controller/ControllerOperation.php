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
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"]) && isset($_GET["param2"]) && $_GET["param2"] !== "") {
            $operation = Operation::get_operation_byid($_GET["param2"]);
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
            $errors = array_merge($errors, Operation::validate_title($title));
            $errors = array_merge($errors, Operation::validate_amount($amount));
            $errorsTitle = array_merge($errorsTitle, Operation::validate_title($title));
            $errorsAmount = array_merge($errorsAmount, Operation::validate_amount($amount));


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

    public function editOperation(): void {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $selected_repartition = 0;
        $disable_CBox_and_SaveTemplate = false;
        $paidByIsSelected = false;
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"]) && isset($_GET["param2"]) && $_GET["param2"] !== "") {
            $operation = Operation::get_operation_byid($_GET["param2"]);
            $tricount = Tricount::getTricountById($operation->tricount, $user->mail);
            $participants = $tricount->get_participants();
            $participants_and_weights = [];
            foreach($participants as $participant){
                $participants_and_weights[] = [$participant, $operation->get_weight($participant->id) == null ? 1 : $operation->get_weight($participant->id)];
            }
            $repartition_templates = $tricount->get_repartition_templates();

            if(isset($_POST["repartitionTemplates"]) && $_POST["repartitionTemplates"] != "customRepartition"){
                $template = Template::get_template_by_id($_POST["repartitionTemplates"]);
                $selected_repartition = $template->id;
                $participants_and_weights = [];
                $disable_CBox_and_SaveTemplate = true;
                foreach($participants as $participant){
                    $participants_and_weights[] = [$participant, $operation->get_weight_from_template($participant, $template) == null ? 0 : $operation->get_weight_from_template($participant, $template)];
                }
            }

            if(isset($_POST["title"]) && isset($_POST["amount"]) && isset($_POST["date"])){
                $title = trim($_POST["title"]);
                $amount = $_POST["amount"];
                $date = $_POST["date"];
                $paidBy = $_POST["paidBy"];
                $errors = array_merge($errors,$operation->validate_title($title));
                $errors = array_merge($errors,$operation->validate_amount($amount));
                if(!isset($_POST["checkboxParticipants"])){
                    if(isset($_POST["weight"])){
                        $errors[] = "You must select at least 1 participant";
                    }
                }
                if(isset($_POST["saveTemplateCheck"])){
                    $newTemplateName = Tools::sanitize($_POST["newTemplateName"]);
                    if(isset($_POST["newTemplateName"]) && $newTemplateName!= ""){
                        if($tricount->template_name_exists($_POST["newTemplateName"])){
                            $errors[] = "This template already exists. Choose another name";
                        }else{
                            $newTemplate = $tricount->add_template($newTemplateName);
                            for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                                for($j = 0; $j<sizeof($_POST["checkboxParticipants"]);++$j){
                                    if($participants_and_weights[$i][0]->id==$_POST["checkboxParticipants"][$j]){
                                        $newTemplate->add_items($participants_and_weights[$i][0], $participants_and_weights[$i][1]);
                                    }
                                }
                            }
                        }
                    }else if(isset($_POST["newTemplateName"]) && empty($newTemplateName)){
                        $errors[] = "A name must be given to template to be able to save it.";
                    }
                }
                for($i = 0; $i < sizeof($_POST["checkboxParticipants"]);++$i){
                    if($_POST["checkboxParticipants"][$i]==$_POST["paidBy"]){
                        $paidByIsSelected = true;
                    }
                }
                if(!$paidByIsSelected){
                    $errors[] = "The payer have to be selected";
                }
                if(count($errors)==0){
                    $operation->title = $title;
                    $operation->amount = $amount;
                    $operation->operation_date = $date;
                    $operation->initiator = $paidBy;
                    $operation->updateOperation();
                    $operation->delete_repartitions();
                    //change les poids dans la liste globale des participants du tricount si ils ont été checkés dans la view 
                    $checkboxes = $_POST["checkboxParticipants"];
                    $weights = $_POST["weight"];
                    for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants_and_weights[$i][0]->id==$checkboxes[$j]){
                                $participants_and_weights[$i][1] = $weights[$i];
                                $operation->add_repartitions($participants_and_weights[$i][0], $participants_and_weights[$i][1]);
                            }
                        }
                    }
                    
                    $this->redirect("Operation", "showOperation",$tricount->id, $operation->id);
                }
            }
            

            (new View("edit_operation"))->show(["selected_repartition" => $selected_repartition,
                                                 "operation" => $operation,
                                                 "user"=>$user,"tricount" => $tricount,
                                                 "participants_and_weights" => $participants_and_weights,
                                                 "repartition_templates"=>$repartition_templates,
                                                 "errors" => $errors,
                                                 "disable_CBox_and_SaveTemplate" => $disable_CBox_and_SaveTemplate]
            );
        } else{
            $this->redirect("Main");
        }
    }

    public function confirm_delete_operation(): void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"]) && isset($_GET["param2"]) && $_GET["param2"] !== "") {
            
            $tricount = Tricount::getTricountById(( $_GET["param1"]));
            if($user->participatesToOperation($_GET["param2"])){
                $operation = Operation::get_operation_byid($_GET["param2"]);
                (new View("delete_operation_confirmation"))->show(["operation"=>$operation,"tricount"=>$tricount]);    
            }
            else{
                $this->redirect();
            }   
        }
    }

    public function delete_operation(){
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"]) && isset($_GET["param2"]) && $_GET["param2"] !== "") {
            
            $tricount = Tricount::getTricountById(( $_GET["param1"]));
            if($user->participatesToOperation($_GET["param2"])){
                $operation= Operation::get_operation_byid($_GET["param2"]);
                $operation->delete_operation();
                $this->redirect();
            }
            else{
                $this->redirect();
            }
            $this->redirect();
        }
    }
}
?>