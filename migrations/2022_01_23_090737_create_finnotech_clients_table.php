<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinnotechClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finnotech_clients', function (Blueprint $table) {
            $table->id();
            $table->json("scopes");
            $table->bigInteger("monthlyCallLimitation")->default(10);
            $table->bigInteger("maxAmountPerTransaction")->default(350000);
            $table->string("userId");
            $table->string("creationDate");
            $table->string("type");
            $table->bigInteger("lifeTime");
            $table->json("deposits");
            $table->string("clientId");
            $table->text("value");
            $table->text("refreshToken");
            $table->string("bank");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finnotech_clients');
    }
}
