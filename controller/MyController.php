<?php

require_once "framework/Controller.php";

abstract class Mycontroller extends Controller{

    public function weightsAreGreaterThanZero(array $weights): bool{
        for($i=0;$i<sizeof($weights);++$i){
            if($weights[$i]<=0){
                return false;
            }
        }
        return true;
    }

    public function weightsAreNumeric(array $weights): bool{
        for($i=0;$i<sizeof($weights);++$i){
            if(!is_numeric($weights[$i])){
                return false;
            }
        }
        return true;
    }

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

    public function validate_amount(float $amount): array {
        $errors = [];
        if($amount<=0){
            $errors[] = "Amount must be greater than 0";
        }
        return $errors;
    }

    public function validate_description(string $description): array{
        $errors = [];
        if(strlen($description)!=0 && strlen($description)<3){
            $errors[] = "Description must either be empty or at least have 3 characters";
        }
        return $errors;
    }
    
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

    public function get_users_and_their_operation_amounts(Operation $operation): array{
        $participants = $operation->get_participants();
        $users = [];
        $total_weight = Operation::get_total_weights($operation->id);
        foreach($participants as $participant){
            $weight = $operation->get_weight($participant);
            $users[] = [User::get_user_by_id($participant),round(($operation->amount/$total_weight)*$weight,2)];
        }
        return $users;
    }
}

?>