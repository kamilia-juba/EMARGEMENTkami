<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'controller/MyController.php';

class ControllerMain extends MyController {
        //si l'utilisateur est connecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() : void {
      
            (new View("addFreinds"))->show();
        }
    }

?>
