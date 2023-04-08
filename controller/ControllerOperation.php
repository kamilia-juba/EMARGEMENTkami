<?php 
require_once 'model/Operation.php';
require_once 'controller/MyController.php';
require_once 'model/User.php';
require_once 'model/Tricount.php';

class ControllerOperation extends Mycontroller{

    public function index() : void {
        $this->get_user_or_redirect();

        $this->redirect("Tricount", "yourTricounts");
    }

    public function showOperation(): void {
        $user = $this->get_user_or_redirect();                                      //redirect a ll'index si l'user n'est pas connecté
        if ($this->validate_url()) {                                                // appel de la méthode vérifiant l'url
            $operation = Operation::get_operation_by_id($_GET["param2"]);            // récuperation de l'id à partir l'url    
            $tricount = Tricount::get_tricount_by_id($operation->tricount,$user->mail);    //récupération du tricount à partir de l'id tricount sur operation et du mail de l'user connecté
            $paidBy = User::get_user_by_id($operation->initiator);                  //recuperation de l'initiator
            $user_participates = $operation->user_participates($user);          //récuperation pour voir si l'utilisateur connecté a participé
            $users = $this->get_users_and_their_operation_amounts($operation);      // recuperation des user et de leur amount
            $operations =  $tricount->get_operations();   //recuperation de toutes les opération sur tricount
            $currentIndex = $this->getCurrentIndex($operations, $operation);        //récuperation de l'index courante
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
        
        if ($this->validate_url()) {                                                // validation url si true exectue le code sinon redirect vers l'index
        $tricount = Tricount::get_tricount_by_id($_GET["param1"], $user->mail);        //recupération de toutes les informations et initialisation afin de pouvoir les utilisé dans le show
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
        $templates_json = $tricount->get_templates_json();
        foreach($participants as $participant){                                     // initialisation des participants, checkbox et leur poids à 1
                $participants_and_weights[] = [$participant, 1, true];
        }
        $repartition_templates = $tricount->get_repartition_templates();            // reprends template de la base de donnée


        if(isset($_POST["repartitionTemplates"]) && $_POST["repartitionTemplates"] != "customRepartition"){ //si un template est appliqué, il execute ce code qui réinistialise la page avec les données du template
            $template = Template::get_template_by_id($_POST["repartitionTemplates"]);
            $selected_repartition = $template->id;
            $participants_and_weights = [];
            foreach($participants as $participant){
                    $participants_and_weights[] = [$participant, $template->get_weight_from_template($participant) == null ? 0 : $template->get_weight_from_template($participant), $participant->user_participates_to_repartition($template->id)];
            }
        }

        if (isset($_POST['title']) && isset($_POST['amount']) && isset($_POST['date']) && 
        isset($_POST['paidBy'])) {
       
            $title = trim($_POST['title']);
            $amount = floatval(trim($_POST['amount']));
            $date = trim($_POST['date']);
            $paidBy = trim($_POST['paidBy']);      
            
            $errors=$this->get_add_operation_errors($tricount);                                            //recupération du reste des erreurs
            $errorsTitle = $errors["errorsTitle"];
            $errorsAmount =$errors["errorsAmount"];
            $errorsCheckboxes= $errors["errorsCheckboxes"];
            $errorsSaveTemplate = $errors["errorsSaveTemplate"];

            if (count($errors["errorsTitle"]+$errors["errorsAmount"]+$errors["errorsCheckboxes"]+$errors["errorsSaveTemplate"]) == 0) { //si pas d'erreurs alors peut exécuter la sauvegarde
                
                
                if(isset($_POST["saveTemplateCheck"])){
                    $newTemplateName = Tools::sanitize($_POST["newTemplateName"]);
                    $weights = $_POST["weight"];

                    $newTemplate = $tricount->add_template($newTemplateName);
                    for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                        for($j = 0; $j<sizeof($_POST["checkboxParticipants"]);++$j){                    
                            if($participants_and_weights[$i][0]->id==$_POST["checkboxParticipants"][$j]){
                                if($weights[$i]>0){
                                    $participants_and_weights[$i][1] = $weights[$i];
                                    $newTemplate->add_items($participants_and_weights[$i][0], $participants_and_weights[$i][1]);    
                                }
                            }
                        }
                    }
                }    

                $operationss = new Operation($title, $tricount->id, $amount, $paidBy,date("Y-m-d H:i:s"), $date);
                $operation=$operationss->persist();
                $checkboxes = $_POST["checkboxParticipants"];
                $weights = $_POST["weight"];
                for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                    for($j = 0; $j<sizeof($checkboxes);++$j){
                        if($participants_and_weights[$i][0]->id==$checkboxes[$j]){
                            if($weights[$i]>0){
                                $participants_and_weights[$i][1] = $weights[$i];
                                $operation->add_repartitions($participants_and_weights[$i][0], $participants_and_weights[$i][1]);
                            }
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
                                            "repartition_templates"=>$repartition_templates,
                                            "selected_repartition" => $selected_repartition,
                                            "templates_json" => $templates_json,
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
        if ($this->validate_url()) { //vérifie l'url (si user fait partie du tricount et si l'url est valide, numérique etc) sinon redirige vers l'index
            $operation = Operation::get_operation_by_id($_GET["param2"]);
            $tricount = Tricount::get_tricount_by_id($operation->tricount, $user->mail);
            $participants = $tricount->get_participants();
            $participants_and_weights = [];
            foreach($participants as $participant){ //récupère un tableau avec les participants ainsi que leur poids sur l'opération et détermine si le participant participe dans cette opération ou non
                $participants_and_weights[] = [$participant, $operation->get_weight(User::get_user_by_id($participant->id)) == null ? 1 : $operation->get_weight(User::get_user_by_id($participant->id)),$operation->user_participates($participant)];
            }
            $repartition_templates = $tricount->get_repartition_templates();

            if(isset($_POST["repartitionTemplates"]) && $_POST["repartitionTemplates"] != "customRepartition"){ //exécute ceci si on applique un template
                $template = Template::get_template_by_id($_POST["repartitionTemplates"]);
                $selected_repartition = $template->id;
                $participants_and_weights = [];
                foreach($participants as $participant){ //récupère un tableau avec les participants et leur poids du template sélectionné et détermine si le participant participe dans ce template ou non
                    $participants_and_weights[] = [$participant, $template->get_weight_from_template($participant) == null ? 0 : $template->get_weight_from_template($participant), $participant->user_participates_to_repartition($template)];
                }
            }

            if(isset($_POST["title"]) && isset($_POST["amount"]) && isset($_POST["date"])){
                $title = trim($_POST["title"]);
                $amount = $_POST["amount"];
                $date = $_POST["date"];
                $paidBy = $_POST["paidBy"];
            
                //partie concernant la gestion des erreurs
                $errors=$this->get_add_operation_errors($tricount);
                $errorsTitle = $errors["errorsTitle"];
                $errorsAmount =$errors["errorsAmount"];
                $errorsCheckboxes= $errors["errorsCheckboxes"];
                $errorsSaveTemplate = $errors["errorsSaveTemplate"];

                //si plus d'erreurs, fait la sauvegarde dans la BDD
                if (count($errors["errorsTitle"]+$errors["errorsAmount"]+$errors["errorsCheckboxes"]+$errors["errorsSaveTemplate"]) == 0) { 

                    if(isset($_POST["saveTemplateCheck"])){
                        $newTemplateName = Tools::sanitize($_POST["newTemplateName"]);
                        $weights = $_POST["weight"];

                        $newTemplate = $tricount->add_template($newTemplateName);
                        for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                            for($j = 0; $j<sizeof($_POST["checkboxParticipants"]);++$j){                    
                                if($participants_and_weights[$i][0]->id==$_POST["checkboxParticipants"][$j]){
                                    if($weights[$i]>0){
                                        $participants_and_weights[$i][1] = $weights[$i];
                                        $newTemplate->add_items($participants_and_weights[$i][0], $participants_and_weights[$i][1]);    
                                    }
                                }
                            }
                        }
                    }  

                    $operation->title = $title;
                    $operation->amount = $amount;
                    $operation->operation_date = $date;
                    $operation->initiator = $paidBy;
                    $operation->update_operation();
                    $operation->delete_repartitions();
                    //change les poids dans la liste globale des participants du tricount si ils ont été checkés dans la view 
                    $checkboxes = $_POST["checkboxParticipants"];
                    $weights = $_POST["weight"];
                    for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants_and_weights[$i][0]->id==$checkboxes[$j]){
                                if($weights[$i]>0){
                                    $participants_and_weights[$i][1] = $weights[$i];
                                    $operation->add_repartitions($participants_and_weights[$i][0], $participants_and_weights[$i][1]);    
                                }
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
        if ($this->validate_url()) { //exécute les vérifications sur l'url qui appelle cette méthode, sinon redirige vers l'index
            
            $tricount = Tricount::get_tricount_by_id(( $_GET["param1"]));
            $operation= Operation::get_operation_by_id($_GET["param2"]);

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