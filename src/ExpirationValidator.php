<?php

namespace Okneloper\JwtValidators;

/**
 * Class ExpirationValidator
 * Validates if exp claim is present and the token is not expired.
 * @package Okneloper\JwtValidators
 */
class ExpirationValidator extends TokenValidator
{
    public function __construct($message = "Token has expired")
    {
        parent::__construct($message, new ClaimPresenceValidator('exp'));
    }

    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        return !$token->isExpired();
    }
}
