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
    
}

?>