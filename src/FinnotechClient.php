<?php

namespace Soroosh\FinnotechClient;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Soroosh\FinnotechClient\Exceptions\ClientNotFoundException;
use Soroosh\FinnotechClient\Models\Client;

class FinnotechClient
{
    private FinnotechOAuthProvider $oauthProvider;

    public function __construct(FinnotechOAuthProvider $oauthProvider)
    {
        $this->oauthProvider = $oauthProvider;
    }

    public function createOAuthRedirect($options = [])
    {
        $scope = config("finnotech.scopes");
        if (empty($options['scope'])) {
            $options['scope'] = $scope;
        }
        return redirect($this->oauthProvider->getAuthorizationUrl($options));
    }

    public function requestClientCredentialToken($scopes = [])
    {
        $client_id = config("finnotech.client_id");
        $client_secret = config("finnotech.client_secret");
        $response =  Http::withHeaders([
            "Authorization" => "Basic " . base64_encode("$client_id:$client_secret"),
        ])
            ->withBody(json_encode([
                "grant_type" => "client_credentials",
                "bank" => config("finnotech.bank"),
                "redirect_uri" => config("finnotech.redirect_uri"),
                "scopes" => implode($scopes, ",")
            ]), "application/json")
            ->post($this->oauthProvider->getBaseAccessTokenUrl([]));
        $data = $response->json();
        if ($data["status"] == "DONE") {
            Client::create($data['result']);
            return view("vendor.finnotech.bank-connected", ['status_color' => "status-done", "status_text" => "اتصال به حساب با موفقیت انجام شد"]);
        } else {
            return view("vendor.finnotech.bank-connected", ['status_color' => "status-failed", "status_text" => "ایجاد توکن دسترسی به حساب بانکی ناموفق بود."]);
        }
    }

    public function getClient($scope)
    {
        $client = Client::query()->whereJsonContains("scopes", $scope)->first();
        if (!($client instanceof Client)) {
            throw new ClientNotFoundException("Client Not found", 503);
        }
        $expireDate = $client->created_at->addMilliseconds($client->lifeTime);
        if ($expireDate->isPast()) {
            $client->delete();
            throw new ClientNotFoundException("Client Not found", 503);
        }
        return $client;
    }

    public function createAuthorizedRequest($scope): PendingRequest
    {
        $client =  $this->getClient($scope);
        $token = $client->value;
        return Http::withToken($token, "Bearer")->baseUrl($this->oauthProvider->domain);
    }

    public function callback($code)
    {
        $client_id = config("finnotech.client_id");
        $client_secret = config("finnotech.client_secret");
        $response =  Http::withHeaders([
            "Authorization" => "Basic " . base64_encode("$client_id:$client_secret"),
        ])
            ->withBody(json_encode([
                "grant_type" => "authorization_code",
                "code" => $code,
                "bank" => config("finnotech.bank"),
                "redirect_uri" => config("finnotech.redirect_uri"),
            ]), "application/json")
            ->post($this->oauthProvider->getBaseAccessTokenUrl([]));
        $data = $response->json();
        if ($data["status"] == "DONE") {
            Client::create($data['result']);
            return view("vendor.finnotech.bank-connected", ['status_color' => "status-done", "status_text" => "اتصال به حساب با موفقیت انجام شد"]);
        } else {
            return view("vendor.finnotech.bank-connected", ['status_color' => "status-failed", "status_text" => "ایجاد توکن دسترسی به حساب بانکی ناموفق بود."]);
        }
    }
}
