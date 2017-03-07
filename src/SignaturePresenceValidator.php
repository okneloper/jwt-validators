<?php

namespace Okneloper\JwtValidators;

/**
 * Class SignaturePresenceValidator
 * Validates if the token is signed.
 * This will prevent an exception when trying to verify signature on a token without one.
 * @package Okneloper\JwtValidators
 */
class SignaturePresenceValidator extends TokenValidator
{
    public function __construct($message = "Token is not signed")
    {
        parent::__construct($message);
    }

    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        return explode('.', $token->__toString())[2] !== '';
    }
}
