<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'controller/MyController.php';

class ControllerMain extends MyController {
        //si l'utilisateur est connecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() : void {
        if ($this->user_logged()) {
            $this->redirect("Tricount","yourTricounts");
        } else {
            (new View("login"))->show(["mail" => "", "password" => "", "errors" => $errors = []]);
        }
    }

    //gestion de la connexion d'un utilisateur
    public function login() : void {
        if($this->user_logged() || (isset($_GET["param1"]) && $_GET["param1"] != "")){
            $this->redirect("Tricount","yourTricounts");
        }else {
            $password = ''; 
            $mail = "";
            $errors = [];
            if (isset($_POST['mail']) && isset($_POST['password'])) { 
                $mail = Tools::sanitize($_POST['mail']);
                $password = Tools::sanitize($_POST['password']);
    
                $errors = User::validate_login($mail, $password);
                if (empty($errors)) {
                    $this->log_user(User::get_user_by_mail($mail));
                }
            }
            (new View("login"))->show(["mail" => $mail, "password" => $password, "errors" => $errors]);
        }
    }
    //gestion de l'inscription d'un nouvel utilisateur
    public function signup() : void {
        if($this->user_logged() || (isset($_GET["param1"]) && $_GET["param1"] != "")){
            $this->redirect("Tricount","yourTricounts");
        }else{
            $mail = '';
            $full_name='';
            $IBAN='';
            $password = '';
            $password_confirm = '';
            $justvalidate = $this->get_justvalidate_conf();
            $errors = [];
            $errorsEmail = [];
            $errorsName = [];
            $errorsIban = [];
            $errorsPasswordConfirm = [];
    
            if (isset($_POST['mail']) && isset($_POST['full_name']) && 
                isset($_POST['password']) && isset($_POST['password_confirm'])) {
               
                $mail = Tools::sanitize($_POST['mail']);
                $full_name = Tools::sanitize($_POST['full_name']);
                $IBAN = Tools::sanitize($_POST['IBAN']);
                $password = Tools::sanitize($_POST['password']);
                $password_confirm = Tools::sanitize($_POST['password_confirm']);

                $errorsEmail = array_merge($errorsEmail,User::validate_unicity($mail));
                $errorsEmail = array_merge($errorsEmail, User::validate_mail($mail));
                $errorsName = array_merge($errorsName,User::validate_full_name($full_name));
                $errorsIban = array_merge($errorsIban,User::validate_IBAN($IBAN));
                $errorsPasswordConfirm = array_merge($errorsPasswordConfirm,User::validate_passwords($password, $password_confirm));
                
    
                $errors =array_merge($errors, User::get_signup_errors($mail,$full_name,$IBAN,$password,$password_confirm));
                
                if (count($errors) == 0) { 
                    $user = new User($mail ,Tools::my_hash($password), $full_name , "user" ,$IBAN );
                    $user->persist(); //sauve l'utilisateur
                    $this->log_user($user);
                }
            }
        
    
            (new View("signup"))->show(["mail" => $mail, 
                                        'full_name'=> $full_name,
                                        'IBAN'=> $IBAN, 
                                        "password" => $password, 
                                        "password_confirm" => $password_confirm,
                                        "errorsEmail" => $errorsEmail,
                                        "errorsName" => $errorsName,
                                        "errorsIban" => $errorsIban,
                                        "errorsPasswordConfirm" => $errorsPasswordConfirm,
                                        "errors" => $errors,
                                        "justvalidate" => $justvalidate]);
        }
        
    }

}