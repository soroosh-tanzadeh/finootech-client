<?php

namespace Soroosh\FinnotechClient\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "finnotech_clients";

    protected $fillable = [
        "scopes",
        "monthlyCallLimitation",
        "maxAmountPerTransaction",
        "userId",
        "creationDate",
        "type",
        "bank",
        "lifeTime",
        "deposits",
        "clientId",
        "value",
        "refreshToken",
        "status",
    ];

    protected $casts = [
        "deposits" => "array",
        "scopes" => "array"
    ];
}
