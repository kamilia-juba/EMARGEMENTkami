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

        //gestion de l'édition du profil
    public function edit_profile() : void {
            $user = $this->get_user_or_redirect();
            $errors = [];
            $success = "";


            if (isset($_POST['full_name']) || isset($_POST['IBAN']) ) {
                $full_name = $_POST['full_name'];
                $iban = $_POST['iban'];
                $user->full_name = $full_name;
                $user->iban = $iban;
                $errors = array_merge($errors, $user->validate_IBAN($iban));
                $errors = array_merge($errors, $user->validate_full_name());

                if (count($errors) == 0) { 
                    $user->persist(); //sauve l'utilisateur
                    
                }
            }
            
            // si on est en POST et sans erreurs, on redirige avec un paramètre 'ok'
            if (count($_POST) > 0 && count($errors) == 0){
            $this->redirect("user", "edit_profile", "ok");
            }

            // si param 'ok' dans l'url, on affiche le message de succès
            if (isset($_GET['param1']) && $_GET['param1'] === "ok"){
            $success = "Your profile has been successfully updated.";
            }

            (new View("edit_profile"))->show(["iban" => $user->iban, "full_name" => $user->full_name , "errors" => $errors, "success" => $success]);
        }
    }



?>