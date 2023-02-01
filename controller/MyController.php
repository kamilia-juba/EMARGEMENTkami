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
    
    
}

?>