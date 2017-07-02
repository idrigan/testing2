<?php

namespace testIntegration\Context;

require './vendor/autoload.php';

use Behat\Behat\Context\Context;
use Src\Domain\Post;
use Src\Domain\User;

final class TextContext implements Context{


    /**
     * @Given /create user with "([^"]*)" and "([^"]*)"$/
     */
    public function createUserWithAnd($email, $password)
    {
        $this->user = new User($email,$password);
    }

    /**
     * @Given /with a title "([^"]*)"$/
     */
    public function withATitle($title)
    {
        $this->title = $title;
    }

    /**
     * @Given /with a body "([^"]*)"$/
     */
    public function withABody($body)
    {
        $this->body = $body;
    }

    /**
     * @When /executing the CreatePostUseCase class$/
     */
    public function executingTheCreatepostusecaseClass()
    {
        $this->post = new Post($this->title,$this->body,$this->user);
    }

    /**
     * @Then /A post published in the blog$/
     */
    public function aPostPublishedInTheBlog()
    {
        \PHPUnit_Framework_TestCase::assertInstanceOf(Post::class,$this->post);
    }

}