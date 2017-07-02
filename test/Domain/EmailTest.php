<?php


namespace Test\Domain;

require './vendor/autoload.php';

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Domain\Email;

class EmailTest extends TestCase
{
    /**
     * @test
     * @dataProvider additionProviderValidEmail
     */
    public function shouldCreateValidEmail($email)
    {
        $this->assertInstanceOf(
            Email::class,
            Email::create($email)
        );
    }

    /**
     * @test
     * @dataProvider additionProviderInvalidEmail
     */
    public function shouldCreateInvalidEmail($email)
    {
        $this->expectException(InvalidArgumentException::class);

        Email::create($email);

    }

    /**
     * @test
     */
    public function shouldReturnValidEmail(){

        $email = Email::create("idrigan@gmail.com");

        $this->assertEquals("idrigan@gmail.com",$email->get());
    }

    public function additionProviderValidEmail()
    {
        return [ ["idrigan@gmail.com"],["carteche@zitelia.com"]];
    }

    public function additionProviderInvalidEmail(){
        return [["prue"],["emailinvalid@"],["otro@noemailbalid"]];
    }
}