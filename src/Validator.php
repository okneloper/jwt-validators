<?php

namespace Okneloper\JwtValidators;

use Lcobucci\JWT\Token;

class Validator extends TokenValidator
{
    /**
     * @var ITokenValidator[]
     */
    protected $validators;

    /**
     * Stops validations as soon as first error encountered
     * @var bool
     */
    protected $breakOnFirstError;

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @param array $validators
     */
    public function setValidators(array $validators)
    {
        $this->validators = $validators;
    }

    public function addValidator(ITokenValidator $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * Validator constructor.
     * @param $validators
     */
    public function __construct($validators = [])
    {
        $this->validators = $validators;
    }

    public function validates(\Lcobucci\JWT\Token $token, $breakOnFirstError = true)
    {
        $valid = true;
        foreach ($this->validators as $validator) {
            $valid = $valid && $validator->validates($token, $breakOnFirstError);
            if (!$valid) {
                $this->errors = array_merge($this->errors, $validator->getErrors());

                if ($breakOnFirstError) {
                    break;
                }
            }
        }
        return $valid;
    }

    /**
     * @param Token $token
     * @codeCoverageIgnore
     */
    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        throw new \BadMethodCallException("You should call validates() method instead");
    }
}
