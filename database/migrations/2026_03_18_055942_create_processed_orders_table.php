<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessedOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processed_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string("SapServer",100);
            $table->string("CardCode",100);
            $table->string("CardName",150);
            $table->dateTime("MinDocDate");
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
        Schema::dropIfExists('processed_orders');
    }
}
