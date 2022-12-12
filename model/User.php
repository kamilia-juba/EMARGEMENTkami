<?php

require_once "framework/Model.php";

class User extends Model {


    public function __construct(public string $mail, public string $hashed_password, public string $full_name, public string $role, public ?string $iban = null){
        
    }

    public static function get_user_by_id(int $id) : User|false {
        $query = self::execute("SELECT * FROM Users where id = :id", ["id"=>$id]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }

    public static function get_user_by_mail(String $mail) : User|false {
        $query = self::execute("SELECT * FROM Users where mail = :mail", ["mail"=>$mail]);
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }

    public static function get_users() : array {
        $query = self::execute("SELECT * FROM Users", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"]);
        }
        return $results;
    }

        public function persist() : User {
        if(self::get_user_by_id($this->pseudo))
            self::execute("UPDATE Users SET  mail=:mail, hashed_password=:hashed_password, full_name=:full_name, role=:role, iban=:iban, WHERE id=:id ", 
                            [ "mail"=>$this->mail,
                                "hashed_password"=>$this->hashed_password,
                                "full_name"=>$this->full_name,
                                "role"=>$this->role,
                                "iban"=>$this->iban]);
        else
            self::execute("INSERT INTO Users(mail,hashed_password,full_name,role,iban) VALUES(:mail,:hashed_password,:full_name,:role,:iban)", 
                            [ "mail"=>$this->mail,
                            "hashed_password"=>$this->hashed_password,
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
            ["userMail",$this->mail]);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row){
            $results[] = new Tricount($row["id"], $row["title"], $row["description"], $row["created_at"], $row["creator"] );
        }

        return $results;
    }

}