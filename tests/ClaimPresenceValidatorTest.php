<?php

use Okneloper\JwtValidators\ClaimPresenceValidator;

/**
 * Class ClaimPresenceValidatorTest
 * @covers Okneloper\JwtValidators\ClaimPresenceValidator
 */
class ClaimPresenceValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testIteReturnsCorrectBoolean()
    {
        $token = (new \Lcobucci\JWT\Builder())
            // one registered claim (iat)
            ->setIssuedAt(time())
            // and one custom
            ->set('user_id', 1)
            ->getToken();

        $validator = new ClaimPresenceValidator('iat');
        $this->assertTrue($validator->validates($token));

        $validator = new ClaimPresenceValidator('user_id');
        $this->assertTrue($validator->validates($token));

        $validator = new ClaimPresenceValidator('iss');
        $this->assertFalse($validator->validates($token));
    }
}
