<?php

namespace Soroosh\FinnotechClient\Services;

use Soroosh\FinnotechClient\FinnotechClient;
use Illuminate\Support\Str;
use Soroosh\FinnotechClient\Exceptions\InvalidBillTypeException;

/**
 * Billing related functions
 */
class BillingService extends Service
{
    /**
     * Detailed bill inquiry service provides the user with billing information along with details by taking the type of bill and its related ID.
     * 
     * @param $type - type of bill (Water, Electricity, Gas)
     * @param $parameter - bill ID
     * 
     * look at https://devbeta.finnotech.ir/billing-cc-inquiry-detail.html for more information
     * 
     * @return array
     */
    public function billingInquiry($type, $parameter)
    {
        if (!in_array($type, ["Water", "Electricity", "Gas", "Tel", "TelNow", "Mobile", "MobileNow", "Electricity-standard"])) {
            throw new InvalidBillTypeException(422);
        }
        $clientId = config("finnotech.client_id");
        $response = $this->client
            ->createAuthorizedRequest("billing:cc-inquiry:get")
            ->get("/billing/v2/clients/{$clientId}/billingInquiry", [
                "trackId" => Str::uuid(),
                "type" => $type,
                "parameter" => $parameter,
            ])->json();
        if (isset($response['status']) && $response['status'] == "DONE") {
            return ["status" => true, "result" => $response["result"], "message" => "خطا در دریافت اطلاعات"];
        }
        return ["status" => false, "result" => $response["error"], "message" => "خطا در دریافت اطلاعات"];
    }
}
