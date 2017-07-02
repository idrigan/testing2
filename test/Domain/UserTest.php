<?php


namespace Test\Domain;

require './vendor/autoload.php';

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Domain\User;

class UserTest extends TestCase
{
    /**
     * @test
     * @dataProvider validEmailPassword
     */
    public function shouldCreateUser($email,$password){
        $this->assertInstanceOf(
            User::class,
            new User($email,$password)
        );
    }

    /**
     * @test
     * @dataProvider invalidEmailPassword
     */
    public function shouldNotCreateUser($email,$password){
        $this->expectException(InvalidArgumentException::class);
        new User($email, $password);
    }

    public function validEmailPassword(){
        return [["idrigan@gmail.com","A1233456"],["prueba@p.com","A123ASASLÑASDDFD56"],["test@test.com","AASDSADASKDSALKD999"]];
    }

    public function invalidEmailPassword(){
        return [["gmail.com","A1233456"],["prueba@p.com","12356"],["test@test.com","AS"],["test@tes","AS"],["test@test.com","AASDSADASKDSALKD999AASDSADASKDSALKD999AASDSADASKDSALKD999AASDSADASKDSALKD999AASDSADASKDSALKD999AASDSADASKDSALKD999"]];
    }
}