<?php

namespace Soroosh\FinnotechClient\Facade;

use Illuminate\Support\Facades\Facade;
use Soroosh\FinnotechClient\FinnotechClient as RealFinnotechClient;

class FinnotechClient extends Facade
{
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(RealFinnotechClient::class);
    }
}
