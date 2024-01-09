<?php

require_once "framework/Model.php";



class User extends Model {

    // constructeur d'un user
    public function __construct(
        public string $Id_secretaire, 
        public string $Nom_secretaire, 
        public string $Prenom_secretaire,
        public string $Adresse_mail,
        public string $Mot_de_passe_hache,
        public string $Departement_secretaire 
        ){  

    }
    // recupre le user selon Id_secretaire
    public static function get_user_by_id(string $Id_secretaire) : User|false {
        $query = self::execute("SELECT * FROM Secretaire where Id_secretaire = :Id_secretaire", ["Id_secretaire"=>$Id_secretaire]);
        $data = $query->fetch(); 
        if ($query->rowCount() ==0) {
            return false ;
         } else{
            return new User($data["Id_secretaire"], $data["Nom_secretaire"], $data["Prenom_secretaire"], $data["Adresse_mail"], $data["Mot_de_passe_hache"], $data["Departement_secretaire"]);
         }

     }
 
    // Permet de modifier les données de la secrétaire
    public function update() : User {
        if($this->id!=null){
            self::execute("UPDATE Secretaire SET Mot_de_passe_hache=:Mot_de_passe_hache WHERE Id_secretaire=:Id_secretaire ", 
                            [   "Id_secretaire"=>$this->Id_secretaire,
                                "Nom_secretaire"=>$this->Nom_secretaire,
                                "Prenom_secretaire"=>$this->Prenom_secretaire,
                                "Adresse_mail"=>$this->Adresse_mail,
                                "Mot_de_passe_hache"=>$this->Mot_de_passe_hache,
                                "Departement_secretaire"=>$this->Departement_secretaire
                                ]);
        }
        return $this ;
     }

    // verifie si l'utilisateur existe et le mot de passe est juste pour se connecter
    public static function validate_login(string $Identifiant, string $Mot_de_passe) : array {
        $errors = [];
        $user = User::get_user_by_id($Identifiant);
        if ($user) {
            echo $Mot_de_passe."\n";
            echo md5($Mot_de_passe)."\n";
            echo $user->Mot_de_passe_hache."\n";
            if (!self::check_password($Mot_de_passe, $user->Mot_de_passe_hache)) {
                $errors[] = "Mot de passe incorrect. Veuillez réessayer.";
            }

        }else if($Identifiant==""){
            $errors[] = "Veuillez saisir votre identifiant.";
        }else {
            $errors[] = "Connexion échouée";
         }
        return $errors;
     }

    //Compare le mot de passe en clair avec le haché stocké dans la base de données
    public static function check_password(string $clear_password, string $hash) : bool {
        return $hash === Tools::my_hash($clear_password);
     }


    // verifie que le mot de passe respecte les conditions
    private static function validate_password(string $password) : array {
        $user= "";
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Le mot de passe doit avoir entre 8 et 16 caractères.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Le mot de passe doit contenir une majuscule, un chiffre et un caractère.";
         }

        return $errors ;
    }

    //verifie que la confirmation du mot de passe est identique au nouveau mot de passe
    public static function validate_passwords(string $password, string $password_confirm) : array {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "Les deux mots de passes doivent etre identique.";
        }
        return $errors ;
    }

    //Vérifie que le nouveau mot de passe est différent du mot de passe actuel
    public function validate_password_unicity(string $password, string $new_password): array{
        $errors = [] ;
        
        if(self::check_password($password,$this->$Mot_de_passe_hache)){
            if($password==$new_password){
                $errors[] = "Le nouveau mot de passe doit etre différent du mot de passe actuel.";
             }
         }
        return $errors ;
     }
 }