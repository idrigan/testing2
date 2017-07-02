<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /create user with "([^"]*)" and "([^"]*)"$/
     */
    public function createUserWithAnd($email, $password)
    {
        $this->user = new \Src\Domain\User($email,$password);
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
        $this->post = new \Src\Domain\Post($this->title,$this->body,$this->user);
    }

    /**
     * @Then /A post published in the blog$/
     */
    public function aPostPublishedInTheBlog()
    {
        \PHPUnit_Framework_TestCase::assertInstanceOf(\Src\Domain\Post::class,$this->post);
    }
}
