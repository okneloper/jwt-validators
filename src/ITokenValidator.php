<?php

namespace Okneloper\JwtValidators;

/**
 * Interface ITokenValidator
 * @package Okneloper\JwtValidators
 */
interface ITokenValidator
{
    /**
     * Returns true if the token validates against this validator
     * @param \Lcobucci\JWT\Token $token
     * @return bool
     */
    public function validates(\Lcobucci\JWT\Token $token, $breakOnFirstError = true);

    /**
     * @return string
     */
    public function getErrors();
}