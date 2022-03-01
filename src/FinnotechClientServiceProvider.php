<?php

namespace Soroosh\FinnotechClient;

use Illuminate\Support\ServiceProvider;
use Jobs\FinnotechClientMonitor;
use League\OAuth2\Client\Provider\GenericProvider;
use Soroosh\FinnotechClient\Facade\FinnotechOAuthProvider as FinnotechOAuthProviderFacade;

class FinnotechClientServiceProvider extends ServiceProvider
{
    public function register()
    {
        $urlEndpoint = config("finnotech.mode") == "sandbox" ? "https://sandboxapi.finnotech.ir" : "https://apibeta.finnotech.ir";

        $this->app->when([FinnotechClient::class, FinnotechClientMonitor::class, FinnotechOAuthProviderFacade::class])
            ->needs(FinnotechOAuthProvider::class)
            ->give(function () use ($urlEndpoint) {
                return new FinnotechOAuthProvider([
                    'clientId' => config("finnotech.client_id"),    // The client ID assigned to you by the provider
                    'clientSecret' => config("finnotech.client_secret"),    // The client password assigned to you by the provider
                    'redirectUri' => config("finnotech.redirect_uri"),
                    "domain" => $urlEndpoint,
                    "scopes" => config("finnotech.scopes")
                ]);
            });

        $this->loadMigrationsFrom(__DIR__ . "/../migrations/");
        $this->publishes([__DIR__ . "/../config/config.php" => config_path("finnotech.php")], ["finnotech-config"]);
        $this->publishes([__DIR__ . "/../views/bank-connected.blade.php" => resource_path("views/vendor/finnotech/bank-connected.blade.php")], ["finnotech-view"]);
    }
}
