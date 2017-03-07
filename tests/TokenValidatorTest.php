<?php

use Okneloper\JwtValidators\TokenValidator;

/**
 * Class TokenValidatorTest
 * @covers Okneloper\JwtValidators\TokenValidator
 */
class TokenValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testBreaksWhenBreakOnFirstIsTrue()
    {
        $validator1 = Mockery::mock(TokenValidator::class)
            ->shouldReceive('validates')->andReturn(false)
            ->shouldReceive('getErrors')->andReturn(['Error1'])
            ->getMock();

        $validator2 = new InvalidValidator('Not valid', $validator1);

        $this->assertFalse($validator2->validates(Mockery::mock(\Lcobucci\JWT\Token::class)));

        $this->assertCount(1, $validator2->getErrors());
    }

}

class InvalidValidator extends TokenValidator
{
    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        return false;
    }
}
