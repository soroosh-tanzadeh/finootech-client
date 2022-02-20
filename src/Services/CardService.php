<?php

namespace Services;

use Soroosh\FinnotechClient\FinnotechClient;
use Illuminate\Support\Str;

class CardService
{
    private FinnotechClient $client;

    public function __construct(FinnotechClient $client)
    {
        $this->client = $client;
    }

    public function getCardInformation($cardNumber)
    {
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("card:information:get")
            ->get("/mpg/v2/clients/{$clientId}/cards/{$cardNumber}", ["trackId" => Str::uuid()])
            ->json();
        if ($response['status'] == "DONE") {
            return $response['result'];
        }
        return $response["error"];
    }

    public function IBANInquiry($IBAN)
    {
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("facility:card-to-iban:get")
            ->get("/oak/v2/clients/${clientId}/ibanInquir", ["trackId" => Str::uuid(), "iban" => $IBAN])
            ->json();
        if ($response['status'] == "DONE") {
            return $response['result'];
        }
        return $response['error'];
    }

    public function getIBAN($cardNumber)
    {
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("facility:card-to-iban:get")
            ->get("/facility/v2/clients/{$clientId}/cardToIban", ["trackId" => Str::uuid(), "version" => 2, "card" => $cardNumber])
            ->json();
        if ($response['status'] == "DONE") {
            return $response['result'];
        }
        return $response['error'];
    }
}
