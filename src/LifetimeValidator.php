<?php

namespace Okneloper\JwtValidators;

/**
 * Class LifetimeValidator
 * Validates if token lifetime is less or equal to the number of seconds.
 * Handy for validating tokens issued by other issuers.
 * @package Okneloper\JwtValidators
 */
class LifetimeValidator extends TokenValidator
{
    /**
     * Max allowed token lifetime in seconds
     * @var int
     */
    protected $lifetime;

    /**
     * LifetimeValidator constructor.
     * @param int $lifetime
     */
    public function __construct($lifetime, $message = "Token lifetime exceeds maximum allowed of %d seconds")
    {
        $this->lifetime = $lifetime;
        $this->message = sprintf($message, $this->lifetime);
    }

    /**
     * Returns true if the token validates against this validator
     * @param \Lcobucci\JWT\Token $token
     * @return bool
     */
    public function isValid(\Lcobucci\JWT\Token $token)
    {
        if (!$token->hasClaim('iat') && !$token->hasClaim('nbf')) {
            $this->errors[] = "One of iat or nbf claims must be present";
            return false;
        }

        // starting time for token lifetime
        $start = $token->hasClaim('nbf') ? $token->getClaim('nbf') : $token->getClaim('iat');
        // actual lifetime
        $token_lifetime = $token->getClaim('exp') - $start;

        return $token_lifetime <= $this->lifetime;
    }
}
