<?php

namespace Src\Domain;

use Doctrine\Instantiator\Exception\InvalidArgumentException;

class Post
{
    private $title;

    private $body;

    private $user;

    public function __construct($title,$body, $user)
    {
        $this->checkTitle($title);
        $this->checkBody($body);
        $this->title = $title;
        $this->body = $body;
    }

    public function setTitle($title){
        $this->checkTitle($title);
        $this->title = $title;

    }

    public function setBody($body){
       $this->checkBody($body);
       $this->body = $body;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getBody(){
        return $this->body;
    }

    public function getTitle(){
        return $this->title;
    }

    private function checkTitle($title)
    {

        if (strlen($title) > 50) {
            throw new InvalidArgumentException(
                "$title is not a valid email address"
            );
        }

    }

    private function checkBody($body){

        if ( strlen ( $body ) > 2000 ){
            throw new InvalidArgumentException(
                "$body is not a valid email address"
            );
        }

    }
}