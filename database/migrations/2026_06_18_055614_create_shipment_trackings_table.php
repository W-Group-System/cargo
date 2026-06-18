<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipment_details_id');
            $table->string('tracking_point',10)->nullable();
            $table->dateTime('arrival_date')->nullable();
            $table->text('remarks')->nullable();
            $table->foreign('shipment_details_id')->references('id')->on('shipment_details')->onDelete('cascade');
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
        Schema::dropIfExists('shipment_trackings');
    }
}
