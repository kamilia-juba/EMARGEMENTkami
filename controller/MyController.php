<?php

require_once "framework/Controller.php";

abstract class Mycontroller extends Controller{

    //vérifie si les poids qui sont dans le tableau donné en paramètre sont supérieurs à 0 sinon renvoie false
    public function weightsAreGreaterThanZero(array $weights): bool{
        for($i=0;$i<sizeof($weights);++$i){
            if($weights[$i]<=0){
                return false;
            }
        }
        return true;
    }

    //vérifie si les poids qui sont dans le tableau donné en paramètre sont bien numériques
    public function weightsAreNumeric(array $weights): bool{
        for($i=0;$i<sizeof($weights);++$i){
            if(!is_numeric($weights[$i])){
                return false;
            }
        }
        return true;
    }

    //méthode qui valide les règles métiers liées au title
    public function validate_title(String $title): array {
        $errors = [];
        if(strlen($title)<=0) {
            $errors[] = "A title is required";
        }
        if(strlen($title)!=0 && strlen($title)<3){
            $errors[] = "Title must have at least 3 characters";
        }
        return $errors;
    }

    //méthode qui valide les règles métiers liées au montant
    public function validate_amount(float $amount): array {
        $errors = [];
        if($amount<=0){
            $errors[] = "Amount must be greater than 0";
        }
        return $errors;
    }

    //méthode qui valide les règles métiers liées à la description
    public function validate_description(string $description): array{
        $errors = [];
        if(strlen($description)!=0 && strlen($description)<3){
            $errors[] = "Description must either be empty or at least have 3 characters";
        }
        return $errors;
    }
    
    //méthode qui fait la vérification liées aux URL. Renvoie false si les conditions ne sont pas respectées
    public function validate_url() :bool{

        $user = $this->get_user_or_redirect();
        $numberOfParam=0;
        
        if(isset($_GET["param1"])&&!isset($_GET["param2"])){$numberOfParam=1;}
        if(isset($_GET["param1"])&&isset($_GET["param2"])){$numberOfParam=2;}
        

        if($numberOfParam==1){
            return isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) &&  $user->isSubscribedToTricount($_GET["param1"]);
        }

        if($numberOfParam==2){
            return isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && isset($_GET["param2"]) && $_GET["param2"] !== "" && is_numeric($_GET["param2"]) && $user->isSubscribedToTricount($_GET["param1"]);
        
        }
        return false;
    } 
    
    //méthodes pour controllerOperation

    //méthode qui récupère l'index courant utilisé pour le next et previous
    public function getCurrentIndex(array $operations, Operation $operation): int{
        $result = 0;
        for($i=0;$i<sizeof($operations);++$i){
            if($operations[$i]->id == $operation->id){
                $result = $i;
            }
        }
        return $result;
    }

    //méthode qui prend en paramètre une opération et renvoie un tableau contenant les participants et leurs montants dûs 
    public function get_users_and_their_operation_amounts(Operation $operation): array{
        $participants = $operation->get_participants();
        $users = [];
        $total_weight = $operation->get_total_weights();  
        foreach($participants as $participant){
            $weight = $operation->get_weight(User::get_user_by_id($participant));
            $users[] = [User::get_user_by_id($participant),round(($operation->amount/$total_weight)*$weight,2)];
        }
        return $users;
    }

    //méthode qui renvoie un tableau d'erreurs pour addOperation et editOperation
    public function get_add_operation_errors(Tricount $tricount): array{

        $title = trim($_POST['title']);
        $amount = floatval(trim($_POST['amount']));
        $errorsTitle = [];
        $errorsAmount = [];
        $errorsCheckboxes= [];
        $errorsSaveTemplate = [];

        $array = array("errorsTitle" => $errorsTitle, "errorsAmount" => $errorsAmount, "errorsCheckboxes" => $errorsCheckboxes,"errorsSaveTemplate" => $errorsSaveTemplate);


        if(isset($_POST["saveTemplateCheck"])){                                                         //execute ce code si l'utilisateur check save template
            $newTemplateName = Tools::sanitize($_POST["newTemplateName"]);
            $weights = $_POST["weight"];
            if(isset($_POST["newTemplateName"]) && $newTemplateName!= ""){                              //verifie si le nom est entré et n'est pas vide
                if($tricount->template_name_exists($_POST["newTemplateName"])){
                    $errorsSaveTemplate[] = "This template already exists. Choose another name";                        //verifie si le nom existe déjà et sinon il sauvegarde chaque item dans la bdd et ajoute le template
                }
                if(isset($_POST["newTemplateName"]) && $newTemplateName== ""){
                    $errorsSaveTemplate[] = "A name must be given to template to be able to save it.";
                }
            }
        }  

            $errorsTitle = array_merge($errorsTitle, $this->validate_title($title));
            $errorsAmount = array_merge($errorsAmount, $this->validate_amount($amount));
            !is_numeric($amount) ? $errorsAmount[] = "Amount should be numeric" : "";

            if(!$this->weightsAreGreaterThanZero($_POST["weight"])){
                $errorsCheckboxes[] = "Weights must be greater than 0";
            }

            if(!$this->weightsAreNumeric($_POST["weight"])){
                $errorsCheckboxes[] = "Weights must be numeric";
            }

            if(!isset($_POST["checkboxParticipants"])){
                if(isset($_POST["weight"])){
                    $errorsCheckboxes[] = "You must select at least 1 participant";
                }
            }

            $array = array("errorsTitle" => $errorsTitle, "errorsAmount" => $errorsAmount, "errorsCheckboxes" => $errorsCheckboxes,"errorsSaveTemplate" => $errorsSaveTemplate);
                     
        return $array;
    }
}

?>