<?php

namespace Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Soroosh\FinnotechClient\FinnotechOAuthProvider;
use Soroosh\FinnotechClient\Models\Client;

class FinnotechClientMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FinnotechOAuthProvider $oauthProvider)
    {
        $clients = Client::all();
        foreach ($clients as $client) {
            $client_id = config("finnotech.client_id");
            $client_secret = config("finnotech.client_secret");
            $response =  Http::withHeaders([
                "Authorization" => "Basic " . base64_encode("$client_id:$client_secret"),
            ])
                ->withBody(json_encode([
                    "grant_type" => "refresh_token",
                    "bank" => config("finnotech.bank"),
                    "refresh_token" => ""
                ]), "application/json")
                ->post($oauthProvider->getBaseAccessTokenUrl([]));
            $data = $response->json();
            if ($data["status"] == "DONE") {
                $client->update($data['result']);
            }
        }
    }
}
