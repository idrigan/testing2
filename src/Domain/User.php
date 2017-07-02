<?php

namespace Src\Domain;


class User
{
    private $email;

    private $password;


    public function __construct($email, $password)
    {
        $this->email = Email::create($email);
        $this->password = Password::create($password);
    }

    public function setEmail($email){
        $this->email->set($email);
    }

    public function setPassword($password){
         $this->password->set($password);
    }

    public function getEmail(){
        return $this->email;
    }

    public  function getPassword()
    {
        return $this->password;
    }
}