<?php

namespace Src\Domain;

use Doctrine\Instantiator\Exception\InvalidArgumentException;

class Password
{
    private $password;

    public function __construct($password)
    {
        $this->checkPassword($password);
        $this->password = $password;
    }

    public static function create($password){
        return new self($password);
    }
    public function get(){
        return $this->password;
    }

    public function set($password){
        $this->checkPassword($password);
        $this->password  = $password;
    }

    private function checkPassword($password){

        if ( strlen ($password) < 3  || strlen($password) > 28){
            throw new InvalidArgumentException(
                "$password is not a valid email address"
            );
        }

        if (!preg_match("#[a-zA-Z]+#",$password)){

            throw new InvalidArgumentException(
                "$password is not a valid email address"
            );
        }


        if (!preg_match("#[0-9]+#",$password)){

            throw new InvalidArgumentException(
                "$password is not a valid email address"
            );
        }


    }
}