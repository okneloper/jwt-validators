<?php

namespace Okneloper\JwtValidators;

/**
 * Class ClaimPresenceValidator
 * Validates if a particular claim is present.
 * @package Okneloper\JwtValidators
 */
class ClaimPresenceValidator extends TokenValidator
{
    /**
     * @var string
     */
    protected $claim_name;

    /**
     * ClaimPresenseValidator constructor.
     * @param string $claim_name
     */
    public function __construct($claim_name, $message = "Token is missing the required %s claim")
    {
        $this->claim_name = $claim_name;
        $this->message = sprintf($message, $this->claim_name);
    }

    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        return $token->hasClaim($this->claim_name);
    }
}
