<?php


namespace Domain;


interface PostRepository
{
    public function checkPost(Post $post);
    public function save(Post $post);
}