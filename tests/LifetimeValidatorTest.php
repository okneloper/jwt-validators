<?php

use Okneloper\JwtValidators\LifetimeValidator;

/**
 * Class LifetimeValidatorTest
 * @covers Okneloper\JwtValidators\LifetimeValidator
 */
class LifetimeValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testRequireIatOrNbfClaimAndExpClaims()
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->getToken();

        $validator = new LifetimeValidator(10);
        $this->assertFalse($validator->isValid($token));
    }

    public function testItReturnsCorrectBoolean()
    {
        $lifetime = 10;
        $issued_at = time();

        $validator = new LifetimeValidator($lifetime);

        $token = (new \Lcobucci\JWT\Builder())
            ->setIssuedAt($issued_at)
            ->setExpiration($issued_at + $lifetime)
            ->getToken();

        $this->assertTrue($validator->validates($token));

        $token = (new \Lcobucci\JWT\Builder())
            ->setIssuedAt($issued_at)
            ->setExpiration($issued_at + $lifetime + 1)
            ->getToken();

        $this->assertFalse($validator->validates($token));
    }

    public function testItWorksWithNbfClaim()
    {
        $lifetime = 10;
        $issued_at = time();
        $not_before = $issued_at + 60;

        $token = (new \Lcobucci\JWT\Builder())
            ->setIssuedAt($issued_at)
            ->setNotBefore($not_before)
            ->setExpiration($not_before + $lifetime)
            ->getToken();

        $validator = new LifetimeValidator($lifetime);
        // if the validator is not using the nbf claim to calculate the lifetime, this will fail
        // because lifetime will be $exp - $iat = 70
        $this->assertTrue($validator->validates($token));
    }
}
