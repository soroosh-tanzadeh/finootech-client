<?php

namespace Soroosh\FinnotechClient\Services;

use Soroosh\FinnotechClient\Models\Client;
use Soroosh\FinnotechClient\FinnotechClient;
use Soroosh\FinnotechClient\Exceptions\ClientNotFoundException;
use Illuminate\Support\Str;

/**
 * Bank account related functions
 */
class BankAccountService extends Service
{
    /**
     * Transfer money from $account to $card number
     */
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

    /**
     * Transfer from connected account to another bank-account using IBAN
     */
    public function transferToAnotherAccount($amount, $description, $reasonDescription, $accountOwnerFirstname, $accountOwnerLastname, $accountIBAN, $paymentId)
    {
        $trackId = "transfer_to_deposit_" . Str::uuid();
        $clientId = config("finnotech.client_id");

        $requestBody = [
            "amount" => $amount,
            "description" => $description,
            "reasonDescription" => $reasonDescription,
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
            return ["status" => true, "data" => $result, "message" => null];
        }
        return ["status" => false, "data" => null, "message" => $response["error"]['message']];
    }

    public function transferInquiry($inquiryTrackId)
    {
        $trackId = "inquiry_transfer_to_deposit_" . Str::uuid();
        $clientId = config("finnotech.client_id");
        $response  = $this->client->createAuthorizedRequest("oak:inquiry-transfer:get")
            ->get("/oak/v2/clients/{$clientId}/transferInquiry", ["trackId" => $trackId, "inquiryTrackId" => $inquiryTrackId]);
        if ($response['status'] == "DONE") {
            $result = $response['result'];
            $result['track_id'] = $trackId;
            return ["status" => true, "data" => $result, "message" => null];
        }
        return ["status" => false, "data" => $response, "message" => $response["error"]['message']];
    }
}
