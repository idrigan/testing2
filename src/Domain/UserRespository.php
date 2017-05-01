<?php


namespace Domain;


interface UserRespository
{
    public function save(User $user);
}