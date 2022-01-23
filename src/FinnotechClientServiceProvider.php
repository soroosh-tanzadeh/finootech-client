<?php

namespace Soroosh\FinnotechClient;

use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\GenericProvider;

class FinnotechClientServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $urlEndpoint = config("finnotech.mode") == "sandbox" ? "https://sandboxapi.finnotech.ir" : "https://apibeta.finnotech.ir";

        $this->app->when(FinnotechClient::class)
            ->needs(GenericProvider::class)
            ->give(function () use ($urlEndpoint) {
                return new GenericProvider([
                    'clientId'                => config("finnotech.client_id"),    // The client ID assigned to you by the provider
                    'clientSecret'            => config("finnotech.client_secret"),    // The client password assigned to you by the provider
                    'redirectUri'             => config("finnotech.redirect_uri"),
                    'urlAuthorize'            => "{$urlEndpoint}/dev/v2/oauth2/authorize",
                    'urlAccessToken'          => "{$urlEndpoint}/dev/v2/oauth2/token",
                    'urlResourceOwnerDetails' => "{$urlEndpoint}/dev/v2"
                ]);
            });
    }
}
