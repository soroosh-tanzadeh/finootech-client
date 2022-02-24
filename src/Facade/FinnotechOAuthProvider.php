<?php

namespace Soroosh\FinnotechClient\Facade;

use Illuminate\Support\Facades\Facade;
use Soroosh\FinnotechClient\FinnotechOAuthProvider as RealFinnotechOAuthProvider;

class FinnotechOAuthProvider extends Facade
{
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(RealFinnotechOAuthProvider::class);
    }
}
