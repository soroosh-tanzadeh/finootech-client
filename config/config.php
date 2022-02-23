<?php
return [
    "mode" => env("FINNOTECH_MODE", "sandbox"),
    "client_id" => env("FINNOTECH_CLIENT_ID", "xxxx"),
    "client_secret" => env("FINNOTECH_CLIENT_SECRET", "xxxx"),
    "redirect_uri" => env("FINNOTECH_REDIRECT_URI", "/callback"),
    "scopes" => ['refund:deposit-card:post', "oak:bill-account:execute"],
    "bank" => "062",
    "allowed_to_create_client" => env("FINNOTECH_AUTO_CLIENT_CREDENTIAL_CREATION", false),
    "default_nid" => env("FINNOTECH_DEFAULT_NATIONAL_CODE"),
];
