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
}
