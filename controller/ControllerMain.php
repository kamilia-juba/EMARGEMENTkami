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
        $pseudo = '';
        $password = '';
        $errors = [];
        if (isset($_POST['mail']) && isset($_POST['password'])) { //note : pourraient contenir des chaînes vides
            $mail = $_POST['mail'];
            $password = $_POST['password'];

            $errors = User::validate_login($mail, $password);
            if (empty($errors)) {
                $this->log_user(User::get_user_by_mail($mail));
            }
        }
        (new View("login"))->show(["mail" => $mail, "password" => $password, "errors" => $errors]);
    }

}