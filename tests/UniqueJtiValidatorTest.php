<?php

use Okneloper\JwtValidators\TokenExistenceChecker;
use Okneloper\JwtValidators\UniqueJtiValidator;

/**
 * Class UniqueJtiValidatorTest
 * @covers Okneloper\JwtValidators\UniqueJtiValidator
 */
class UniqueJtiValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testItReturnsFalseWhenExists()
    {
        $token = $this->getToken();

        $checker = $this->mockChecker(true);
        $validator = new UniqueJtiValidator($checker);

        $this->assertFalse($validator->validates($token));
    }

    public function testItReturnsTrueWhenNotExists()
    {
        $token = $this->getToken();

        $checker = $this->mockChecker(true);
        $validator = new UniqueJtiValidator($checker);

        $this->assertFalse($validator->validates($token));
    }

    public function testItReturnsFalseWhenJtiIsMissing()
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->getToken();

        $checker = $this->mockChecker(false);

        $validator = new UniqueJtiValidator($checker);

        $this->assertFalse($validator->validates($token));

        $this->assertEquals("Token is missing the required jti claim", $validator->getErrors()[0]);
    }

    /**
     * @return \Lcobucci\JWT\Token
     */
    protected function getToken()
    {
        $jti = 'idX';

        $token = (new \Lcobucci\JWT\Builder())
            ->setId($jti)
            ->getToken();
        return $token;
    }

    /**
     * @return \Mockery\MockInterface
     */
    protected function mockChecker($resultOfExistsFunc)
    {
        $checker = Mockery::mock(TokenExistenceChecker::class)
            ->shouldReceive('exists')->andReturn($resultOfExistsFunc)
            ->getMock();
        return $checker;
    }
}
