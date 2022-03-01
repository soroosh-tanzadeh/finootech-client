<?php

namespace Soroosh\FinnotechClient\Models;

use Illuminate\Database\Eloquent\Model;

class BillingInquiry extends Model
{
    protected $table = "billing_inquiries";

    protected $fillable = [
        "Amount",
        "BillId",
        "PayId",
        "Date",
        "track_id"
    ];
}
