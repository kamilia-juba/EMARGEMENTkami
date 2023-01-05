<?php

require_once "framework/Model.php";
require_once "model/Tricount.php";
require_once "model/Operation.php";

class User extends Model {


    public function __construct(
        public string $mail, 
        public string $hashed_password, 
        public string $full_name, 
        public string $role, 
        public ?string $iban = null,
        public ?int $id = null){  
    }

    public static function get_user_by_id(int $id) : User|false {
        $query = self::execute("SELECT * FROM Users where id = :id", ["id"=>$id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"], $data["id"]);
        }
    }

    public static function get_user_by_mail(String $mail) : User|false {
        $query = self::execute("SELECT * FROM Users where mail = :mail", ["mail"=>$mail]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {

            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"],$data["id"]);

        }
    } 

    public static function get_users() : array {
        $query = self::execute("SELECT * FROM Users", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {

            $results[] = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"],$row["id"]);

        }
        return $results;
    }

        public function persist() : User {
        if(self::get_user_by_mail($this->mail))
            self::execute("UPDATE Users SET  hashed_password=:hashed_password, full_name=:full_name, role=:role, iban=:iban WHERE mail=:mail ", 
                            [ "mail"=>$this->mail,
                                "hashed_password"=>Tools::my_hash($this->hashed_password),
                                "full_name"=>$this->full_name,
                                "role"=>$this->role,
                                "iban"=>$this->iban]);
        else
            self::execute("INSERT INTO Users(mail,hashed_password,full_name,role,iban) VALUES(:mail,:hashed_password,:full_name,:role,:iban)", 
                            [ "mail"=>$this->mail,
                            "hashed_password"=>Tools::my_hash($this->hashed_password),
                            "full_name"=>$this->full_name,
                            "role"=>$this->role,
                            "iban"=>$this->iban]);
        return $this;
    }

    public static function validate_login(string $mail, string $password) : array {
        $errors = [];
        $user = User::get_user_by_mail($mail);
        if ($user) {
            if (!self::check_password($password, $user->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the mail '$mail'. Please sign up.";
        }
        return $errors;
    }

    private static function check_password(string $clear_password, string $hash) : bool {
        return $hash === Tools::my_hash($clear_password);
    }
    public function get_user_tricounts() : array {
        $query = self::execute("select * from tricounts where tricounts.creator = (select id from users where mail = :userMail)", 
            ["userMail" => $this->mail]);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row){
            $results[] = new Tricount($row["title"], $row["created_at"], $row["creator"], $row["description"],$row["id"]);
        }

        return $results;
    }
// SAADAYACINECHAKER
    public function validate_full_name() : array {
        $errors = [];
        if (!strlen($this->full_name) > 0) {
            $errors[] = "A full name is required.";
        } if ((strlen($this->full_name) < 3 || strlen($this->full_name)> 256)) {

            $errors[] = "Full name length must be at least 3.";
        } 
        
        /*if ((preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->full_name))) {
            $errors[] = "full_name must start by a letter and must contain only letters and numbers.";

        }*/
        return $errors;
}
    public function validate_mail(string $mail) : array {
        $errors = [];
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/",$mail)) {
            $errors[] = "This mail is not valide";
        }
        return $errors;
    }

   public function validate_IBAN(string $IBAN) : array {
        $errors = [];
        $Countries = array(
            'al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,
            'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,
            'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,
            'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,
            'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24
        );
        
        
            // Enlever tous les caractères qui ne sont pas des chiffres ou des lettres
            $IBAN = preg_replace('/[^a-zA-Z0-9]/', '', $IBAN);
        
            // Extraire les deux premiers caractères (qui représentent le code du pays)
            $pays = substr($IBAN,0, 2);
        
            // Vérifier que les deux premiers caractères sont des lettres et que le pays est reconnu
            if (!ctype_alpha($pays)) {
            $errors[] = "2 first characters are not letters";
            } 

            if (strlen($IBAN) != $Countries[ strtolower(substr($IBAN,0,2)) ])
            {
                $errors[] = "Wrong IBAN size or unknown country";
            }
           
        
        return $errors;
        
        
    }

    public static function validate_unicity(string $mail) : array {
        $errors = [];
        $user = self::get_user_by_mail($mail);
        if ($user) {
            $errors[] = "This user already exists.";
        } 
        return $errors;
    }

  
    private static function validate_password(string $password) : array {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }

    public static function validate_passwords(string $password, string $password_confirm) : array {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }
}