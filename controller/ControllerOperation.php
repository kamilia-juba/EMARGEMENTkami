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
        if ($this->validate_url()) {
            $operation = Operation::get_operation_byid($_GET["param2"]);
            $tricount = Tricount::getTricountById($operation->tricount,$user->mail);
            $paidBy = User::get_user_by_id($operation->initiator);
            $user_participates = $operation->user_participates($user->id);
            $users = $this->get_users_and_their_operation_amounts($operation);
            $operations = Operation::get_operations_by_tricountid($tricount->id);
            $currentIndex = $this->getCurrentIndex($operations, $operation);
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
        $selected_repartition = 0;
        
        if ($this->validate_url()) {
        $tricount = Tricount::getTricountById($_GET["param1"], $user->mail);
        $title = "";
        $amount = "";
        $date = "";
        $paidBy = "";
        $errors = [];
        $errorsTitle = [];
        $errorsAmount = [];
        $errorsCheckboxes= [];
        $errorsSaveTemplate = [];
        $participants = $tricount->get_participants();
        $participants_and_weights = [];
        foreach($participants as $participant){
                $participants_and_weights[] = [$participant, 1, true];
        }
        $repartition_templates = $tricount->get_repartition_templates();


        if(isset($_POST["repartitionTemplates"]) && $_POST["repartitionTemplates"] != "customRepartition"){
            $template = Template::get_template_by_id($_POST["repartitionTemplates"]);
            $selected_repartition = $template->id;
            $participants_and_weights = [];
            foreach($participants as $participant){
                    $participants_and_weights[] = [$participant, Template::get_weight_from_template($participant, $template) == null ? 0 : Template::get_weight_from_template($participant, $template), $participant->user_participates_to_repartition($template->id)];
            }
        }

        if (isset($_POST['title']) && isset($_POST['amount']) && isset($_POST['date']) && 
        isset($_POST['paidBy'])) {
       
            $title = trim($_POST['title']);
            $amount = floatval(trim($_POST['amount']));
            $date = trim($_POST['date']);
            $paidBy = trim($_POST['paidBy']);


            if(isset($_POST["saveTemplateCheck"])){
                $newTemplateName = Tools::sanitize($_POST["newTemplateName"]);
                $weights = $_POST["weight"];
                if(isset($_POST["newTemplateName"]) && $newTemplateName!= ""){
                    if(!$tricount->template_name_exists($_POST["newTemplateName"])){
                        $newTemplate = $tricount->add_template($newTemplateName);
                        for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                            for($j = 0; $j<sizeof($_POST["checkboxParticipants"]);++$j){
                                if($participants_and_weights[$i][0]->id==$_POST["checkboxParticipants"][$j]){
                                    $participants_and_weights[$i][1] = $weights[$i];
                                    $newTemplate->add_items($participants_and_weights[$i][0], $participants_and_weights[$i][1]);
                                }
                            }
                        }
                    }else{
                        $errorsSaveTemplate[] = "This template already exists. Choose another name";
                    }
                }
            }           
            
            $errors=$this->get_add_operation_errors($tricount);
            $errorsTitle = $errors["errorsTitle"];
            $errorsAmount =$errors["errorsAmount"];
            $errorsCheckboxes= $errors["errorsCheckboxes"];
            $errorsSaveTemplate = $errors["errorsSaveTemplate"];

            if (count($errors["errorsTitle"]+$errors["errorsAmount"]+$errors["errorsCheckboxes"]+$errors["errorsSaveTemplate"]) == 0) { 
                $operationss = new Operation($title, $tricount->id, $amount, $paidBy,date("Y-m-d H:i:s"), $date);
                $operation=$operationss->persist();
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
                $this->redirect("Tricount", "showTricount", $tricount->id);
            }
            
        }


        (new View("add_operation"))->show(["title" => $title, 
                                            'amount'=> $amount,
                                            'date'=> $date, 
                                            "errorsTitle" => $errorsTitle,
                                            "errorsAmount" => $errorsAmount, 
                                            "errorsCheckboxes" => $errorsCheckboxes,
                                            "errorsSaveTemplate" => $errorsSaveTemplate,
                                            "tricount"=> $tricount, 
                                            "participants" => $participants,
                                            "participants_and_weights" => $participants_and_weights,
                                            "repartition_templates"=>$repartition_templates,                                            "selected_repartition" => $selected_repartition,
                                            "user"=>$user]);
        }else{
            $this->redirect("main");
        }
    }

    public function editOperation(): void {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $errorsTitle = [];
        $errorsAmount = [];
        $errorsCheckboxes= [];
        $errorsSaveTemplate = [];
        $selected_repartition = 0;
        if ($this->validate_url()) {
            $operation = Operation::get_operation_byid($_GET["param2"]);
            $tricount = Tricount::getTricountById($operation->tricount, $user->mail);
            $participants = $tricount->get_participants();
            $participants_and_weights = [];
            foreach($participants as $participant){
                $participants_and_weights[] = [$participant, $operation->get_weight($participant->id) == null ? 1 : $operation->get_weight($participant->id),$operation->user_participates($participant->id)];
            }
            $repartition_templates = $tricount->get_repartition_templates();

            if(isset($_POST["repartitionTemplates"]) && $_POST["repartitionTemplates"] != "customRepartition"){
                $template = Template::get_template_by_id($_POST["repartitionTemplates"]);
                $selected_repartition = $template->id;
                $participants_and_weights = [];
                foreach($participants as $participant){
                    $participants_and_weights[] = [$participant, Template::get_weight_from_template($participant, $template) == null ? 0 : Template::get_weight_from_template($participant, $template), $participant->user_participates_to_repartition($template->id)];
                }
            }

            if(isset($_POST["title"]) && isset($_POST["amount"]) && isset($_POST["date"])){
                $title = trim($_POST["title"]);
                $amount = $_POST["amount"];
                $date = $_POST["date"];
                $paidBy = $_POST["paidBy"];
                if(isset($_POST["saveTemplateCheck"])){
                    $newTemplateName = Tools::sanitize($_POST["newTemplateName"]);
                    $weights = $_POST["weight"];
                    if(isset($_POST["newTemplateName"]) && $newTemplateName!= ""){
                        if(!$tricount->template_name_exists($_POST["newTemplateName"])){
                            $newTemplate = $tricount->add_template($newTemplateName);
                            for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                                for($j = 0; $j<sizeof($_POST["checkboxParticipants"]);++$j){
                                    if($participants_and_weights[$i][0]->id==$_POST["checkboxParticipants"][$j]){
                                        $participants_and_weights[$i][1] = $weights[$i];
                                        $newTemplate->add_items($participants_and_weights[$i][0], $participants_and_weights[$i][1]);
                                    }
                                }
                            }
                        }else{
                            $errorsSaveTemplate[] = "This template already exists. Choose another name";
                        }
                    }
                }
            
                $errors=$this->get_add_operation_errors($tricount);
                $errorsTitle = $errors["errorsTitle"];
                $errorsAmount =$errors["errorsAmount"];
                $errorsCheckboxes= $errors["errorsCheckboxes"];
                $errorsSaveTemplate = $errors["errorsSaveTemplate"];

                if (count($errors["errorsTitle"]+$errors["errorsAmount"]+$errors["errorsCheckboxes"]+$errors["errorsSaveTemplate"]) == 0) { 
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
                                                 "errorsTitle" => $errorsTitle,
                                                 "errorsAmount" => $errorsAmount, 
                                                 "errorsCheckboxes" => $errorsCheckboxes,
                                                 "errorsSaveTemplate" => $errorsSaveTemplate]
            );
        } else{
            $this->redirect("Main");
        }
    }

    public function delete_operation(){
        $user = $this->get_user_or_redirect();
        if ($this->validate_url()) {
            
            $tricount = Tricount::getTricountById(( $_GET["param1"]));
            $operation= Operation::get_operation_byid($_GET["param2"]);

            if(isset($_POST["yes"])){
                $operation->delete_operation();
                $this->redirect("Tricount","showTricount",$tricount->id);
            }
            if(isset($_POST["no"])){
                $this->redirect("Operation","editOperation",$tricount->id,$operation->id);
            }
            (new View("delete_operation_confirmation"))->show(["operation"=>$operation,"tricount"=>$tricount]);
            
        }
        else{
            $this->redirect();
        }
    }
}
?>