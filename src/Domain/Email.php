<?php

namespace Src\Domain;


use Doctrine\Instantiator\Exception\InvalidArgumentException;

final class Email
{
    private $email;

    private function __construct($email)
    {
        $this->isValidEmail($email);
        $this->email = $email;
    }

    public static function create($email){

        return new self($email);
    }

    public function get()
    {
        return $this->email;
    }

    public function set($email){
        $this->isValidEmail($email);
        $this->email = $email;
    }

    private function isValidEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                "$email is not a valid email address"
            );
        }
    }
}