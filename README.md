# Finnotech.ir Client
Laravel/PHP Finnotech Api Client

## Finnotech
Home Page: https://finnotech.ir

Api Doc: https://apibeta.finnotech.ir

Sandbox Dashboard Url: https://sandboxbeta.finnotech.ir

Mainnet Dashboard Url: https://devbeta.finnotech.ir

## Usage

Create a OAuth athorization redirect
```php
FinnotechClient::createOAuthRedirect(["scope" => ['refund:deposit-card:post'], "bank" => config("finnotech.bank")]);
```

Handle Callback
```php
Route::get('/callback', function (Request $request) {
    return FinnotechClient::callback($request->input("code"));
});
```