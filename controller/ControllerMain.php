<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'controller/MyController.php';

class ControllerMain extends Controller {
    public function index() : void {
        $errors = [] ;

        if($this->user_logged()){
            $user = $this->get_user_or_false();
            $this->redirect("Home","yourHome");
        }
        else{
        (new View("login"))->show(["Identifiant" => "", "Mot de passe" =>"","errors"=>$errors]);
        }
    }
 
    public function login() : void {
        
            $Identifiant = "";
            $Mot_de_passe = ''; 
            $errors = [] ;

            if (isset($_POST['Identifiant']) && isset($_POST['Mot_de_passe'])) { 
                
                $Identifiant = Tools::sanitize($_POST['Identifiant']);
                $Mot_de_passe = Tools::sanitize($_POST['Mot_de_passe']);
                $errors = User::validate_login($Identifiant , $Mot_de_passe);


                If (empty($errors)){
                    echo "connexion réussie";
                    $this->log_user(User::get_user_by_id($Identifiant));
                }

             }

             (new View("login"))->show(["Identifiant" => $Identifiant, "Mot de passe" => $Mot_de_passe, "errors"=>$errors]);
    }
 }