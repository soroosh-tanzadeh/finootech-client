<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingInquiries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string("track_id");
            $table->bigInteger("Amount");
            $table->string("BillId");
            $table->string("PayId");
            $table->string("Date");
            $table->string("type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_inquiries');
    }
}
