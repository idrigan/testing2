<?php

namespace Domain;


class User
{
    const ERRORLENGTHPASSWORD = "La longitud de la contraseña tiene que se mayor que 3 y enor que 28";
    const ERRORVALIDPASSWORD = "La longitud de la contraseña tiene que se mayor que 3 y enor que 28";
    const ERRORVALIDEMAIL = "El email introducido no es válido";
    private $email;

    private $password;

    public function __construct($email, $password)
    {
        $this->checkEmail($email);
        $this->checkPassword($password);

        $this->email = $email;
        $this->password = $password;
    }

    private function checkPassword($password){
        if ( strlen ($password) < 3  || strlen($password) > 28){
            throw new \ErrorException(self::ERRORLENGTHPASSWORD);
        }

        if (!preg_match("[a-zA-Z0-9]",$password,$m)){
            throw new \ErrorException(self::ERRORVALIDPASSWORD);
        }

    }

    private function checkEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \ErrorException(self::ERRORVALIDEMAIL);
        }
    }
}