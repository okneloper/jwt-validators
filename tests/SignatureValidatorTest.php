<?php

use Okneloper\JwtValidators\SignatureValidator;

/**
 * Class SignaturePresenceValidatorTest
 * @covers Okneloper\JwtValidators\SignaturePresenceValidator
 */
class SignatureValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testItReturnsCorrectBoolean()
    {
        $validator = new SignatureValidator('secret', new \Lcobucci\JWT\Signer\Hmac\Sha256());

        $token = (new \Lcobucci\JWT\Builder())
            ->getToken();

        // empty token should validate as it's not signed
        $this->assertFalse($validator->validates($token));

        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $token = (new \Lcobucci\JWT\Builder())
            ->sign($signer, 'secret')
            ->getToken();

        $this->assertTrue($validator->validates($token));

        // changing token by appending a character
        $token = (new \Lcobucci\JWT\Parser())->parse($token->__toString() . 'f');
        //  does not validate any more
        $this->assertFalse($validator->validates($token));
    }
}
