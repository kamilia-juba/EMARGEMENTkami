<?php

require_once "framework/Model.php";

class User extends Model {


    public function __construct(public string $mail, public string $hashed_password, public string $full_name, public string $role, public ?string $iban = null){
        
    }
}