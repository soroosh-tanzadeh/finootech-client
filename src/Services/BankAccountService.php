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

    public function transferToAnotherAccount($amount, $description, $accountOwnerFirstname, $accountOwnerLastname, $accountIBAN, $paymentId)
    {
        $trackId = "transfer_to_deposit_" . Str::uuid();
        $clientId = config("finnotech.client_id");

        $requestBody = [
            "amount" => $amount,
            "description" => $description,
            "destinationFirstname" => $accountOwnerFirstname,
            "destinationLastname" => $accountOwnerLastname,
            "destinationNumber" => $accountIBAN,
            "paymentNumber" => $paymentId
        ];
        $response  = $this->client->createAuthorizedRequest("oak:transfer-to:execute")
            ->withBody(json_encode($requestBody), "application/json")
            ->post("/oak/v2/clients/{$clientId}/transferTo?trackId={$trackId}");
        if ($response['status'] == "DONE") {
            $result = $response['result'];
            $result['track_id'] = $trackId;
            return $result;
        }
        return $response['error'];
    }
}
