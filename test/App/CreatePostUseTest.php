<?php


namespace Test\App;

use App\CreatePost;
use Domain\EventQueue;
use Domain\Post;
use Domain\PostEvent;
use Domain\PostRepository;
use Domain\User;

class CreatePostUseTest extends \PHPUnit_Framework_TestCase
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

    private $user;

    private $post;

    private $eventQueue;

    public function setUp(){

        $this->user = new User(self::EMAIl_VALID,self::PASSWORD_VALID);
        $this->post = new Post(self::TITLE_VALID,self::BODY_VALID,$this->user);
        $this->createPostRepository = $this->createMock(PostRepository::class);
        $this->eventQueue = $this->createMock(EventQueue::class);
        $this->createPost = new CreatePost($this->createPostRepository, $this->eventQueue);


    }

    protected function tearDown()
    {
        $this->post = null;
        $this->createPostRepository = null;
        $this->eventQueue = null;
        $this->newPostUseCase = null;
    }

    public function shouldNoCreateUser(){
       parent::assertEquals(new User(self::EMAIl_INVALID,self::PASSWORD_VALID),USER::ERRORVALIDEMAIL);
       parent::assertEquals(new User(self::EMAIl_VALID,self::PASSWORD_INVALID),USER::ERRORLENGTHPASSWORD);
       parent::assertEquals(new User(self::EMAIl_INVALID,self::PASSWORD_INVALID2),USER::ERRORVALIDPASSWORD);
    }

    public function shouldNoCreatePost(){
        parent::assertEquals(new Post(self::TITLE_INVALID,self::BODY_VALID ,USER::ERRORVALIDEMAIL),Post::ERRORINVALIDTITLE);
        parent::assertEquals(new Post(self::TITLE_VALID,self::BODY_INVALID ,USER::ERRORVALIDEMAIL),Post::ERRORINVALIDBODY);
    }


    /** @test */
    public function shouldPersistAPostOneTimeIfItDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenThePostShouldBeSavedOnce();
        $this->whenTheNewPostUseCaseIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldNotPersistAPostIfItAlreadyExists()
    {
        $this->givenAPostRepositoryThatHasASpecificPost();
        $this->thenThePostShouldNotBeSaved();
        $this->whenTheNewPostUseCaseIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldPublishANewPostEventByDefaultIfThePostDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenAnEventShouldBePublished();
        $this->whenTheNewPostUseCaseIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldNotPublishANewPostEventIfThePostAlreadyExists()
    {
        $this->givenAPostRepositoryThatHasASpecificPost();
        $this->thenAnEventShouldNotBePublished();
        $this->whenTheNewPostUseCaseIsExecutedWithASpecificPost();
    }

    /** @test */
    public function shouldPublishANewPostEventIfForcedToPublishAndThePostDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenAnEventShouldBePublished();
        $this->whenTheNewPostIsExecutedAndForcedToPublishWithASpecificPost();
    }

    /** @test */
    public function shouldNotPublishANewPosEventIfForcedNotToPublishAndThePostDoesNotExist()
    {
        $this->givenAPostRepositoryThatDoesntHaveASpecificPost();
        $this->thenAnEventShouldNotBePublished();
        $this->whenTheNewPostIsExecutedAndForcedNotToPublishWithASpecificPost();
    }



    private function whenTheNewPostIsExecutedAndForcedToPublishWithASpecificPost()
    {
        $this->createPostRepository->execute($this->post, true);
    }

    private function whenTheNewPostIsExecutedAndForcedNotToPublishWithASpecificPost()
    {
        $this->createPostRepository->execute($this->post, false);
    }

    private function givenAPostRepositoryThatDoesntHaveASpecificPost()
    {
        $this->createPostRepository
            ->method('checkPost')
            ->willReturn(false);
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