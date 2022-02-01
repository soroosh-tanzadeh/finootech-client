<?php

namespace Soroosh\FinnotechClient\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "finnotech_clients";

    protected $appends = ["expiration_status"];

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

    protected $hidden = ["value", "refreshToken", "created_at", "updated_at"];

    protected $casts = [
        "deposits" => "array",
        "scopes" => "array"
    ];

    public function getExpirationStatusAttribute()
    {
        $expireDate = $this->created_at->addMilliseconds($this->lifeTime);
        if ($expireDate->isPast()) {
            return "expired";
        }
        return "OK";
    }
}
