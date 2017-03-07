<?php

use Okneloper\JwtValidators\ITokenValidator;
use Okneloper\JwtValidators\Validator;

/**
 * Class ValidatorTest
 * @covers Okneloper\JwtValidators\Validator
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testItAddsValidators()
    {
        $validator = new Validator();

        $validator1 = Mockery::mock(ITokenValidator::class);
        $validator->addValidator($validator1);
        $this->assertCount(1, $validator->getValidators());

        $validator2 = Mockery::mock(ITokenValidator::class);
        $validator->addValidator($validator2);
        $this->assertCount(2, $validator->getValidators());

        $validator->setValidators([$validator1]);
        $this->assertCount(1, $validator->getValidators());

        $validator = new Validator([$validator1, $validator2]);
        $this->assertCount(2, $validator->getValidators());
    }

    public function testItBreaksOnFirstErrorByDefault()
    {
        $validator1 = $this->mockValidator('Error1');
        $validator2 = $this->mockValidator('Error2');

        $validator = new Validator([$validator1, $validator2]);
        $validator->validates(Mockery::mock(\Lcobucci\JWT\Token::class));

        $this->assertCount(1, $validator->getErrors());
    }

    public function testItNotBreaksOnFirstErrorWhenBreakSetToFalse()
    {
        $validator1 = $this->mockValidator('Error1');
        $validator2 = $this->mockValidator('Error2');

        $validator = new Validator([$validator1, $validator2], false);
        $validator->validates(Mockery::mock(\Lcobucci\JWT\Token::class), false);

        $this->assertCount(2, $validator->getErrors());
    }

    /**
     * @return mixed
     */
    protected function mockValidator($error)
    {
        return Mockery::mock(ITokenValidator::class)
            ->shouldReceive('validates')->andReturn(false)
            ->shouldReceive('getErrors')->andReturn([$error])
            ->getMock();
    }
}
