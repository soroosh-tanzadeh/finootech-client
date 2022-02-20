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
            $table->json("scopes")->nullable();
            $table->bigInteger("monthlyCallLimitation")->default(10);
            $table->bigInteger("maxAmountPerTransaction")->default(350000);
            $table->string("userId")->nullable();
            $table->string("creationDate")->nullable();;
            $table->string("type")->nullable();
            $table->bigInteger("lifeTime")->nullable();
            $table->json("deposits")->nullable();
            $table->string("clientId")->nullable();
            $table->text("value");
            $table->text("refreshToken")->nullable();
            $table->string("bank")->nullable();
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
