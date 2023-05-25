<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'controller/MyController.php';

class ControllerUser extends MyController {
    public function index() : void {
        $this->get_user_or_redirect();

        $this->redirect("Tricount", "yourTricounts");
    }

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
        $mail = '';
        $full_name='';
        $iban='';
        $errors = [];
        $errorsEmail = [];
        $errorsName = [];
        $errorsIban = [];

        $full_name=$user->full_name;
        $iban=$user->iban;
        $mail=$user->mail;
        
        $justvalidate = $this->get_justvalidate_conf();
        $sweetalert = $this->get_sweetalert_conf();


        if (isset($_POST['full_name']) || isset($_POST['iban']) || isset($POST['mail'] )) {
            $full_name = Tools::sanitize($_POST['full_name']);
            $iban = Tools::sanitize($_POST['iban']);
            $mail = Tools::sanitize($_POST['mail']);

            
            $errorsEmail = array_merge($errorsEmail, User::validate_mail($mail));
            $errorsName = array_merge($errorsName,User::validate_full_name($full_name));
            
            $errors = array_merge($errorsEmail, $errorsName, $errorsIban);
            if (count($errorsEmail) == 0 && count($errorsName) == 0 && count($errorsIban) == 0) {
                $user->full_name = $full_name;
                $user->iban = $iban;
                $user->mail=$mail;
                $user->persist();
                $this->redirect("user","settings"); 
            }
        }

        (new View("edit_profile"))->show(["iban" => $iban, 
                                        "full_name" => $full_name,
                                        "mail"=>$mail , 
                                        "errorsMail" => $errorsEmail,
                                        "errorsName" => $errorsName, 
                                        "errorsIban" => $errorsIban, 
                                        "justvalidate" => $justvalidate,
                                        "sweetalert" => $sweetalert]);
    }

    public function change_password() : void {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $success = "";
        $actual_password = "";
        $password = '';
        $password_confirm = '';
        $justvalidate = $this->get_justvalidate_conf();
        $sweetalert = $this->get_sweetalert_conf();

        if (isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['actual_password'])) {
           
            $actual_password = $_POST['actual_password'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));
            $errors = array_merge($errors, $user->validate_password_unicity($actual_password,$password));

            if (count($errors) == 0) { 
                $user->hashed_password = Tools::my_hash($password);
                $user->persist(); //sauve l'utilisateur
                $this->redirect("User","settings");
            }
        }
        (new View("change_password"))->show([ "errors" => $errors, 
                                            "success" => $success, 
                                            "justvalidate" => $justvalidate, 
                                            "sweetalert" => $sweetalert,
                                            "actual_password" => $actual_password,
                                            "password" => $password,
                                            "password_confirm" => $password_confirm]);
    }

    public function check_correct_password_service(){
        parent::check_correct_password_service();
    }

    public function passwords_matches_service(){
        parent::passwords_matches_service();
    }
    public function Mail_exists_service(){
        $res = "false";
       

        if(isset($_POST["newMail"]) && $_POST["newMail"] !== ""){
            $user = User::get_user_by_mail($_POST["newMail"]);
            if($user!=null){
             $res = "true";
             }
         }
        echo $res;
    }


}
?>