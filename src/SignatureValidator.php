<?php

namespace Okneloper\JwtValidators;

use Lcobucci\JWT\Signer;

/**
 * Created 18/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
class SignatureValidator extends TokenValidator
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @var Signer
     */
    private $signer;

    public function __construct($secret, Signer $signer, $message = "Token signature could not be validated")
    {
        parent::__construct($message, new SignaturePresenceValidator());
        $this->secret = $secret;
        $this->signer = $signer;
    }

    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        return $token->verify($this->signer, $this->secret);
    }
}
