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
            $table->string("CardName",150)->nullable();
            $table->dateTime("MinDocDate")->nullable();
            $table->date("AvailabilityDate")->nullable();
            $table->date("PickupDate")->nullable();
            $table->string("CargoStatus",100)->nullable();
            $table->string("OrderStatus",100)->default('S');
            $table->char("is_coload")->nullable();
            $table->unsignedBigInteger("coloaded_by")->nullable();
            $table->integer("coload_order")->nullable();
            $table->text("remarks")->nullable();
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
