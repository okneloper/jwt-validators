<?php

namespace Okneloper\JwtValidators;

use Lcobucci\JWT\Token;

/**
 * Interface TokenExistenceChecker
 * Check if a token exists in a custom repository.
 * @package Okneloper\JwtValidators
 */
interface TokenExistenceChecker
{
    /**
     * @param Token $token
     * @return bool
     */
    public function exists(Token $token);
}
