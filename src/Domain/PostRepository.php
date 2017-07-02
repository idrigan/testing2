<?php


namespace Src\Domain;

interface PostRepository
{
    public function checkPost(Post $post);
    public function save(Post $post);
}