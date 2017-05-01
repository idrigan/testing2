<?php

namespace App;


use Domain\EventQueue;
use Domain\Post;
use Domain\PostEvent;
use Domain\PostRepository;

class CreatePost
{
    private $postRepository;

    private $eventQueue;


    public function __construct(PostRepository $postRepository, EventQueue $eventQueue)
    {
        $this->postRepository = $postRepository;
        $this->eventQueue = $eventQueue;

    }

    public function execute(Post $post, $publish = true)
    {
        $exists = $this->postRepository->checkPost($post);
        if ($exists == true) return;
        $this->postRepository->save($post);
        if ($publish == false) return;
        $this->eventQueue->publish(new PostEvent($post));
    }

}