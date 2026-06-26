<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create3rdPartyEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('3rd_party_endpoint', function (Blueprint $table) {
            $table->increments('id');
            $table->string("Name",100);
            $table->string("Code",100);
            $table->string("Endpoint",200);
            $table->string("ApiKey",100);
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
        Schema::dropIfExists('3rd_party_endpoint');
    }
}
