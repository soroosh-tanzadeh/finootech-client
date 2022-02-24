<?php

namespace Soroosh\FinnotechClient\Services;

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
            return ["status" => true, "result" => $response["result"], "message" => null];
        }
        return ["status" => false, "result" => $response["error"], "message" => "خطا در دریافت اطلاعات"];
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
            return ["status" => true, "result" => $response["result"], "message" => null];
        }
        return ["status" => false, "result" => $response["error"], "message" => "خطا در دریافت اطلاعات"];
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
        if ($response['status'] == "DONE") {
            return ["status" => true, "result" => $response["result"], "message" => null];
        }
        return ["status" => false, "result" => $response["error"], "message" => "خطا در دریافت اطلاعات"];
    }
}
