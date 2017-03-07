<?php

namespace Okneloper\JwtValidators;

/**
 * Class TokenValidator
 * @package Okneloper\JwtValidators
 */
abstract class TokenValidator implements ITokenValidator
{
    /**
     * Validation result messages
     * @var array
     */
    protected $errors = [];

    /**
     * This particular validator message
     * @var string
     */
    protected $message;

    /**
     * @var ITokenValidator
     */
    protected $previous_validator;

    abstract protected function isValid(\Lcobucci\JWT\Token $token);

    /**
     * TokenValidator constructor.
     * @param array $messages
     * @param ITokenValidator $previous_validator
     */
    public function __construct($message, ITokenValidator $previous_validator = null)
    {
        $this->message = $message;
        $this->previous_validator = $previous_validator;
    }

    public function validates(\Lcobucci\JWT\Token $token, $breakOnFirstError = true)
    {
        $this->errors = [];

        $valid = true;
        if (isset($this->previous_validator)) {
            if (!$this->previous_validator->validates($token, $breakOnFirstError)) {
                $valid = false;
                $this->errors = array_merge($this->errors, $this->previous_validator->getErrors());
            }
        }

        if (!$valid && $breakOnFirstError) {
            return false;
        }

        if (!$this->isValid($token)) {
            $this->errors[] = $this->message;
            $valid = false;
        }

        return $valid;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
