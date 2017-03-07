<?php

use Okneloper\JwtValidators\SignaturePresenceValidator;

/**
 * Class SignaturePresenceValidatorTest
 * @covers Okneloper\JwtValidators\SignaturePresenceValidator
 */
class SignaturePresenceValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testItReturnsCorrectBoolean()
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->getToken();

        $validator = new SignaturePresenceValidator();
        $this->assertFalse($validator->validates($token));

        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $token = (new \Lcobucci\JWT\Builder())
            ->sign($signer, 'secret')
            ->getToken();

        $validator = new SignaturePresenceValidator();
        $this->assertTrue($validator->validates($token));
    }
}
