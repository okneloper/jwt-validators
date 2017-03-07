<?php

namespace Okneloper\JwtValidators;

/**
 * Class UniqueJtiValidator
 * Validates if the token does not exist in a custom storage.
 * @package Okneloper\JwtValidators
 */
class UniqueJtiValidator extends TokenValidator
{
    protected $checker;

    /**
     * UniqueJtiValidator constructor.
     * @param $user
     */
    public function __construct(TokenExistenceChecker $checker, $message = "This token has already been used")
    {
        $this->checker = $checker;
        // chain a jti presence validator
        parent::__construct($message, new ClaimPresenceValidator('jti'));
    }

    /**
     * Returns true if the token validates against this validator
     * @param \Lcobucci\JWT\Token $token
     * @return bool
     */
    public function isValid(\Lcobucci\JWT\Token $token)
    {
        // if the token already exists on our records it's been processed and is not unique
        return !$this->checker->exists($token);
    }
}
