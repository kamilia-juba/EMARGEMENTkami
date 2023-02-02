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

    public function signup() : void {
        $mail = '';
        $full_name='';
        $IBAN='';
        $password = '';
        $password_confirm = '';
        $errors = [];
        if (isset($_POST['mail']) && isset($_POST['full_name']) && isset($_POST['IBAN']) && 
            isset($_POST['password']) && isset($_POST['password_confirm'])) {
           
            $mail = Tools::sanitize($_POST['mail']);
            $full_name = Tools::sanitize($_POST['full_name']);
            $IBAN = Tools::sanitize($_POST['IBAN']);
            $password = Tools::sanitize($_POST['password']);
            $password_confirm = Tools::sanitize($_POST['password_confirm']);

            $errors = User::validate_unicity($mail);
            $errors = array_merge($errors, User::validate_full_name($full_name));
            $errors = array_merge($errors, User::validate_mail($mail));
            $errors = array_merge($errors, User::validate_IBAN($IBAN));
            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));

            if (count($errors) == 0) { 
                $user = new User($mail ,Tools::my_hash($password), $full_name , "user" ,$IBAN );
                $user->persist(); //sauve l'utilisateur
                $this->log_user($user);
            }
        }
        (new View("signup"))->show(["mail" => $mail, 'full_name'=> $full_name,'IBAN'=> $IBAN, "password" => $password, 
                                         "password_confirm" => $password_confirm, "errors" => $errors]);
    }

}