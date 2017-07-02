<?php


namespace Src\Domain;


interface UserRespository
{
    public function save(User $user);
}