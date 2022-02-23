<?php

namespace Soroosh\FinnotechClient\Services;

use Soroosh\FinnotechClient\FinnotechClient;

class Service
{
    protected FinnotechClient $client;

    public function __construct(FinnotechClient $client)
    {
        $this->client = $client;
    }
}
