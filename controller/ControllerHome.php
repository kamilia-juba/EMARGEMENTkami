<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'controller/MyController.php';

class ControllerHome extends MyController {

    public function yourHome():void{
        $user = $this->get_user_or_redirect();
        (new View("home"))->show(["user" => $user]);
    }

    public function index() : void {
        
        $user = $this->get_user_or_redirect();
        $this->redirect("Home","yourHome");
    }
 }