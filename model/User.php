<?php

require_once "framework/Model.php";
require_once "model/Tricount.php";
require_once "model/Operation.php";

class User extends Model {

    // constructeur d'un user
    public function __construct(
        public string $mail, 
        public string $hashed_password, 
        public string $full_name, 
        public string $role, 
        public ?string $iban = null,
        public ?int $id = null,
        public ?float $account=0){  

    }
    // recupre le user selon l'id 
    public static function get_user_by_id(int $id) : User|false {
        $query = self::execute("SELECT * FROM Users where id = :id", ["id"=>$id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"], $data["id"]);
        }
    }
    // recupere le user selon le mail 
    public static function get_user_by_mail(String $mail) : User|false {
        $query = self::execute("SELECT * FROM Users where mail = :mail", ["mail"=>$mail]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {

            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"],$data["id"]);

        }
    } 
    // recupere tout les utilisateurs 
    public static function get_users() : array {
        $query = self::execute("SELECT * FROM Users", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {

            $results[] = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"],$row["id"]);

        }
        return $results;
    }
        // sa permet de save l'user dans la base de donner 
        public function persist() : User {
        if(self::get_user_by_mail($this->mail)){
            self::execute("UPDATE Users SET  hashed_password=:hashed_password, full_name=:full_name, role=:role, iban=:iban WHERE mail=:mail ", 
                            [ "mail"=>$this->mail,
                                "hashed_password"=>$this->hashed_password,
                                "full_name"=>$this->full_name,
                                "role"=>$this->role,
                                "iban"=>$this->iban]);
        }else{
            self::execute("INSERT INTO Users(mail,hashed_password,full_name,role,iban) VALUES(:mail,:hashed_password,:full_name,:role,:iban)", 
                            [ "mail"=>$this->mail,
                            "hashed_password"=>$this->hashed_password,
                            "full_name"=>$this->full_name,
                            "role"=>$this->role,
                            "iban"=>$this->iban]);
            $this->id=Model::lastInsertId();
        }
        return $this;
    }
    // verifie si l'utilisateur existe et le mot de passe est juste pour se connecter
    public static function validate_login(string $mail, string $password) : array {
        $errors = [];
        $user = User::get_user_by_mail($mail);
        if ($user) {
            if (!self::check_password($password, $user->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }

        }else if($mail==""){
            $errors[] = "Please enter a mail.";
        }else {
            $errors[] = "Can't find a member with the mail '$mail'. Please sign up.";
        }
        return $errors;
    }

    private static function check_password(string $clear_password, string $hash) : bool {
        return $hash === Tools::my_hash($clear_password);
    }
    // recupere les utilisateurs participant a un tricount
    public function get_user_tricounts() : array {
        $query = self::execute("select * from tricounts where id in (select tricount from subscriptions where user = :userId) order by created_at DESC", 
            ["userId" => $this->id]);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row){
            $results[] = new Tricount($row["title"], $row["created_at"], $row["creator"], $row["description"],$row["id"]);
        }

        return $results;
    }
    // verifie si le string full name respercte les condition
    public static function validate_full_name(string $full_name) : array {
        $errors = [];
        if (!strlen($full_name) > 0) {
            $errors[] = "A full name is required.";
        } if ((strlen($full_name) < 3 || strlen($full_name)> 256)) {

            $errors[] = "Full name length must be at least 3.";
        } 
        
        /*if ((preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->full_name))) {
            $errors[] = "full_name must start by a letter and must contain only letters and numbers.";

        }*/
        return $errors;
}
    // verifie si le string full name respercte les condition(forme d'un mail)
    public static function validate_mail(string $mail) : array {
        $errors = [];
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/",$mail)) {
            $errors[] = "This mail is not valide";
        }
        return $errors;
    }
    // verifie si le string IBAN respercte les condition(forme d'un IBAN)
   public static function validate_IBAN(string $IBAN) : array {
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
            if(array_key_exists(strtolower($pays),$Countries)){
                if (strlen($IBAN) != $Countries[ strtolower(substr($IBAN,0,2)) ])
                {
                    $errors[] = "Wrong IBAN size";
                }
            }
            else{
                $errors[] = "Unknown country";
            }
        
        return $errors;
        
        
    }
    // verifie que le mail doit etre unique 
    public static function validate_unicity(string $mail) : array {
        $errors = [];
        $user = self::get_user_by_mail($mail);
        if ($user) {
            $errors[] = "This user already exists.";
        } 
        return $errors;
    }

    // verifie que le password resple condition
    private static function validate_password(string $password) : array {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }
    //verifie que le password resple condition et d'il soit identique au password
    public static function validate_passwords(string $password, string $password_confirm) : array {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    //vérifie si l'user fait partie du tricount donné en paramètre
    public function isSubscribedToTricount(int $id): bool{
        $query = self::execute("SELECT * FROM subscriptions WHERE user=:userId and tricount=:tricountId", ["userId" => $this->id, "tricountId" => $id]);
        $data = $query->fetch();
        return !(empty($data));
    }

    //vérifie si l'user fait partie de l'opération donnée en paramètre
    //Pourrait ne pas être utilisé. A voir
    public function participatesToOperation(int $operationId): bool{
        $query = self::execute("SELECT * FROM repartitions WHERE operation=:operationId AND user=:userId",["operationId"=>$operationId,"userId"=>$this->id]);
        $data = $query->fetch();
        return !(empty($data));
    }
    // recupere le user creator du tricount
    public static function get_creator_of_tricount(int $tricountID) : User {
        $query = self::execute("select * from users where id in (select creator from tricounts where id=:tricountID) ", ["tricountID"=>$tricountID]);
        $data = $query->fetch();

        return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"],$data["id"]);
    }
    //recupere les utilisateur qui non pas particite au tricpount
    public static function get_users_not_sub_to_a_tricount(int $tricountId) : array {
        $query = self::execute("SELECT * FROM users WHERE id NOT IN (SELECT user FROM subscriptions WHERE tricount=:tricountId) ORDER BY full_name", ["tricountId"=>$tricountId]);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {

            $results[] = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"],$row["id"]);

        }
        return $results;
    }

    //recupere les utilisateur qui on participer a un template 
    public function isSubscribedToTemplate(int $templateId): bool{
        $query = self::execute("SELECT * FROM repartition_template_items WHERE user=:userId and repartition_template=:templateId", ["userId" => $this->id, "templateId" => $templateId]);
        $data = $query->fetch();
        return !(empty($data));

    }
    // recupere tout les user d'un template 
    public function user_participates_to_repartition(int $templateId){
        $query = self::execute("SELECT * FROM repartition_template_items WHERE repartition_template=:templateId and user=:userId",["templateId" => $templateId, "userId" => $this->id]);
        $data = $query->fetch();
        return !empty($data);
    }

    //ajoute un accès dans la table subscriptions en fonction du dernier ID(tricount) inséré
    public function add_subscription(){
        $lastInserted = Model::lastInsertId();
        self::execute("INSERT INTO subscriptions(tricount,user) VALUES(:lastInserted, :user)", ["lastInserted" => $lastInserted, "user" => $this->id]);
    }
    // recuper les utilisateurs qui ont des participe au moins une fois
    public function has_already_paid(int $tricountId): bool{
        $query = self::execute("SELECT * FROM operations WHERE initiator=:userId and tricount=:tricountId", ["userId" => $this->id, "tricountId" => $tricountId]);
        $data = $query->fetch();
        return !(empty($data));

    }
    // recupere les erreurs lors d'un signup
    public static function getSignupErrors(string $mail, string $full_name, string $iban, string $password, string $password_confirm ): array{
        $errors = [];
        $errors = User::validate_unicity($mail);
        $errors = array_merge($errors, User::validate_full_name($full_name));
        $errors = array_merge($errors, User::validate_mail($mail));
        $errors = array_merge($errors, User::validate_IBAN($iban));
        $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));
        return $errors;
    }
}