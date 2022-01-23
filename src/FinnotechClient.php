<?php

namespace Soroosh\FinnotechClient;

use League\OAuth2\Client\Provider\GenericProvider;

class FinnotechClient
{
    private GenericProvider $oauthProvider;

    public function __construct(GenericProvider $oauthProvider)
    {
        $this->oauthProvider = $oauthProvider;
    }
}
