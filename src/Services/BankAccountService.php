<?php

namespace Soroosh\FinnotechClient\Services;

use Soroosh\FinnotechClient\Models\Client;
use Soroosh\FinnotechClient\FinnotechClient;
use Soroosh\FinnotechClient\Exceptions\ClientNotFoundException;
use Illuminate\Support\Str;

class BankAccountService
{
    private FinnotechClient $client;

    public function __construct(FinnotechClient $client)
    {
        $this->client = $client;
    }

    public function depositToCard($account, $card, $amount, $cif)
    {
        $traceNumber = uniqid(mt_rand(), true) . microtime(true);
        $trackId = Str::uuid();
        $requestBody = [
            "account" => $account,
            "card" => $card,
            "amount" => $amount,
            "cif" => $cif,
            "traceNumber" => $traceNumber
        ];
        $serviceClient = $this->client->getClient("refund:deposit-card:post");
        $clientId = "";
        if (!($serviceClient instanceof Client)) {
            throw new ClientNotFoundException("Client Not found", 503);
        } else {
            $clientId = $serviceClient->clientId;
        }
        return $this->client->createAuthorizedRequest("refund:deposit-card:post")
            ->withBody(json_encode($requestBody), "application/json")
            ->post("/cardrefund/v2/clients/{$clientId}/depositToCard?trackId={$trackId}")
            ->json();
    }
}
