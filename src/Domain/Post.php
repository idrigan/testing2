<?php


namespace Domain;


class Post
{
    private $title;

    private $body;

    private $user;

    const ERRORINVALIDTITLE = "El título es mayor de 50 caracteres";
    const ERRORINVALIDBODY = "El cuerpo tiene mas de 2000 caracteres";

    public function __construct($title, $body, $user)
    {
        $this->checkTitle($title);
        $this->checkBody($body);

        $this->title = $title;
        $this->body = $body;
        $this->user = $user;

    }

    private function checkTitle($title){

        if ( strlen( $title ) > 50 ){
            throw new \ErrorException(self::ERRORINVALIDTITLE);
        }
    }


    private function checkBody($body){

        if ( strlen ( $body ) > 2000 ){
            throw new \ErrorException(self::ERRORINVALIDBODY);
        }
    }
}