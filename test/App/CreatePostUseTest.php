<?php


namespace Test\App;

require './vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use Src\App\CreatePostUseCase;
use Src\Domain\EventQueue;
use Src\Domain\Post;
use Src\Domain\PostEvent;
use Src\Domain\PostRepository;
use Src\Domain\User;


class CreatePostUseTest extends TestCase
{

    const EMAIl_VALID = "idrigan@gmail.com";
    const EMAIl_INVALID = "idrigangmailcom";

    const PASSWORD_VALID = "prueba6";
    const PASSWORD_INVALID = "p";
    const PASSWORD_INVALID2 = "prueba";

    const TITLE_VALID = "titulo";
    const BODY_VALID = "titulo";

    const TITLE_INVALID = "titulo de mas de cincuenta caracteres..............aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa........";
    const BODY_INVALID = "titulo de mas de dos mil caracteres..............aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa........aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss";

    private $createPostRepository;

    private $createPostUseCase;

    private $user;

    private $post;

    private $eventQueue;

    public function setUp(){

        $this->user = new User(self::EMAIl_VALID,self::PASSWORD_VALID);
        $this->post = new Post(self::TITLE_VALID,self::BODY_VALID,$this->user);
        $this->createPostRepository = $this->createMock(PostRepository::class);
        $this->eventQueue = $this->createMock(EventQueue::class);
        $this->createPostUseCase = new CreatePostUseCase($this->createPostRepository, $this->eventQueue);


    }

    protected function tearDown()
    {
        $this->post = null;
        $this->createPostRepository = null;
        $this->eventQueue = null;
        $this->newPostUseCase = null;
    }

    /** @test */
    public function shouldPersistAPostOneTimeIfItDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenThePostShouldBeSavedOnce();
        $this-> whenTheNewPostIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldNotPersistAPostIfItAlreadyExists()
    {
        $this->givenAPostRepositoryThatHaveASpecificPost();
        $this->thenAnEventShouldNotBePublished();
        $this-> whenTheNewPostIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldPublishANewPostEventByDefaultIfThePostDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenAnEventShouldBePublished();
        $this-> whenTheNewPostIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldNotPublishANewPostEventIfThePostAlreadyExists()
    {
        $this->givenAPostRepositoryThatHaveASpecificPost();
        $this->thenAnEventShouldNotBePublished();
        $this->whenTheNewPostIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldPublishANewPostEventIfForcedToPublishAndThePostDoesNotExist()
    {
        $this->givenAPostRepositoryThatHaveASpecificPost();
        $this->thenAnEventShouldNotBePublished();
        $this->whenTheNewPostIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldNotPublishANewPosEventIfForcedNotToPublishAndThePostDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenAnEventShouldBePublished();
        $this->whenTheNewPostIsExecutedAndForcedToPublishWithASpecificPost();
    }

    /**
     * @test
     */
    public function shouldNotPublishANewPostEventIfForcedToPublishAndThePostAlreadyExists()
    {
        $this->givenAPostRepositoryThatHaveASpecificPost();
        $this->thenAnEventShouldNotBePublished();
        $this->whenTheNewPostIsExecutedAndForcedNotToPublishWithASpecificPost();
    }

    private function givenAPostRepositoryThatDoesntHaveASpecificPost()
    {
        $this->createPostRepository
            ->method('checkPost')
            ->willReturn(false);
    }

    private function givenAPostRepositoryThatHaveASpecificPost()
    {
        $this->createPostRepository
            ->method('checkPost')
            ->willReturn(true);
    }

    private function whenTheNewPostIsExecutedWithASpecificPost()
    {
        $this->createPostUseCase->execute($this->post);
    }

    private function whenTheNewPostIsExecutedAndForcedToPublishWithASpecificPost()
    {
        $this->createPostUseCase->execute($this->post, true);
    }

    private function whenTheNewPostIsExecutedAndForcedNotToPublishWithASpecificPost()
    {
        $this->createPostUseCase->execute($this->post, false);
    }

    private function thenThePostShouldBeSavedOnce()
    {
        $this->createPostRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Post::class));
    }

    private function thenThePostShouldNotBeSaved()
    {
        $this->createPostRepository
            ->expects($this->never())
            ->method('save');
    }

    private function thenAnEventShouldBePublished()
    {
        $this->eventQueue
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(PostEvent::class));
    }

    private function thenAnEventShouldNotBePublished()
    {
        $this->eventQueue
            ->expects($this->never())
            ->method('publish');
    }

}