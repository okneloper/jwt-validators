<?php

use Okneloper\JwtValidators\ExpirationValidator;

/**
 * Class ExpirationValidatorTest
 * @covers Okneloper\JwtValidators\ExpirationValidator
 * @covers Okneloper\JwtValidators\TokenValidator
 */
class ExpirationValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testIreturnsFalseWhenExpClaimNotSet()
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->getToken();

        $validator = new ExpirationValidator();
        $this->assertFalse($validator->validates($token));
    }

    public function testItReturnsTrueWhenNotExpired()
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->setExpiration(time() + 10)
            ->getToken();

        $validator = new ExpirationValidator();
        $this->assertTrue($validator->validates($token));
    }

    public function testItReturnsFalseWhenExpired()
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->setExpiration(time() - 10)
            ->getToken();

        $validator = new ExpirationValidator();
        $this->assertFalse($validator->validates($token));
    }
}
