<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'controller/MyController.php';

class ControllerUser extends MyController {
    public function index() : void {}

    public function settings() : void {
        if (!$this->user_logged()) {
            $this->redirect("Main");
        } else {
            $user = $this->get_user_or_redirect();

            (new View("settings"))->show(["user" => $user]);
        }
    }
}


?>