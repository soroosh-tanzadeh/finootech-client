<?php

namespace Soroosh\FinnotechClient\Models;

use Illuminate\Database\Eloquent\Model;

class BillingInquiry extends Model
{
    protected $table = "billing_inquiries";

    protected $hidden = [
        "created_at",
        "updated_at",
        "parameter",
        "type"
    ];

    protected $fillable = [
        "Amount",
        "BillId",
        "type",
        "PayId",
        "Date",
        "parameter",
        "track_id"
    ];
}
