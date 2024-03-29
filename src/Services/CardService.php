<?php

namespace Soroosh\FinnotechClient\Services;

use Illuminate\Support\Facades\Log;
use Soroosh\FinnotechClient\FinnotechClient;
use Illuminate\Support\Str;

/**
 * Card Related functions
 */
class CardService extends Service
{

    /**
     * displays the card information by receiving the card number.
     *
     * @param $cardNumber
     */
    public function getCardInformation($cardNumber)
    {
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("card:information:get")
            ->get("/mpg/v2/clients/{$clientId}/cards/{$cardNumber}", ["trackId" => Str::uuid()])
            ->json();
        if ($response['status'] == "DONE") {
            return ["status" => true, "data" => $response["result"], "message" => null];
        }
        return ["status" => false, "data" => null, "message" => $response["error"]['message']];
    }

    /**
     * displays the Sheba number information.
     *
     * @param $IBAN
     */
    public function IBANInquiry($IBAN)
    {
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("oak:iban-inquiry:get")
            ->get("/oak/v2/clients/${clientId}/ibanInquiry", ["trackId" => Str::uuid(), "iban" => $IBAN])
            ->json();
        if ($response['status'] == "DONE") {
            return ["status" => true, "data" => $response["result"], "message" => null];
        }
        return ["status" => false, "data" => null, "message" => $response["error"]['message']];
    }

    /**
     * displays the Sheba number information by receiving the card number.
     *
     * @param $cardNumber
     */
    public function getIBAN($cardNumber)
    {
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("facility:card-to-iban:get")
            ->get("/facility/v2/clients/{$clientId}/cardToIban", ["trackId" => Str::uuid(), "version" => 2, "card" => $cardNumber])
            ->json();
        Log::debug("Finnotech", $response);
        if ($response['status'] == "DONE" && in_array($response["result"]['depositStatus'], ["02", "03"])) {
            return ["status" => true, "data" => $response["result"], "message" => null];
        }
        return ["status" => false, "data" => null, "message" => isset($response["error"]) ?  $response["error"]['message'] : "حساب قابل واریز نمی‌باشد"];
    }
}
