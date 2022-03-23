<?php

namespace Soroosh\FinnotechClient\Services;

use Soroosh\FinnotechClient\FinnotechClient;
use Illuminate\Support\Str;
use Soroosh\FinnotechClient\Exceptions\InvalidBillTypeException;
use Soroosh\FinnotechClient\Models\BillingInquiry;

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
        $trackId = Str::uuid();
        $response = $this->client
            ->createAuthorizedRequest("billing:cc-inquiry:get")
            ->get("/billing/v2/clients/{$clientId}/billingInquiry", [
                "trackId" => $trackId,
                "type" => $type,
                "parameter" => $parameter,
            ])->json();
        if (isset($response['status']) && $response['status'] == "DONE") {
            BillingInquiry::create(array_merge($response["result"], ['track_id' => $trackId, "parameter" => $parameter, "type" => $type]));
            return ["status" => true, "data" => $response["result"], "message" => null];
        }
        return ["status" => false, "data" => null, "message" => $response["error"]['message']];
    }

    /**
     * Pay Bill
     *
     * @param $payId
     * @param $billId
     */
    public function billPayment($payId, $billId)
    {
        $clientId = config("finnotech.client_id");
        $trackId = Str::uuid();
        $response = $this->client
            ->createAuthorizedRequest("oak:bill-account:execute")
            ->post("/oak/v2/clients/{$clientId}/billPayment?trackId={$trackId}", [
                "payId" => $payId,
                "billId" => $billId
            ])->json();
        if (isset($response['status']) && $response['status'] == "DONE") {
            return ["status" => true, "data" => $response["result"], "message" => null];
        }
        return ["status" => false, "data" => null, "message" => $response["error"]['message']];
    }
}
