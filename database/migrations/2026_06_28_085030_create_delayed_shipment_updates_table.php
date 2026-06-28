<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelayedShipmentUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delayed_shipment_updates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipment_details_id');
            $table->dateTime('prev_eta')->nullable();
            $table->dateTime('updated_eta')->nullable();
            $table->char('is_notif_sent')->default('0');
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
        Schema::dropIfExists('delayed_shipment_updates');
    }
}
