<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_order_id');
            $table->string('delivery_status',10)->nullable();
            $table->string('tracking_points',10)->nullable();
            $table->string('location',255)->nullable();
            $table->string('invoice_number',255)->nullable();
            $table->string('mode',255)->nullable();
            $table->string('cbw_doc_status',100)->nullable();
            $table->string('pallet_type',100)->nullable();
            $table->date('cargo_readiness_date')->nullable();
            $table->date('posting_date')->nullable();
            $table->string('current_location',255)->nullable();
            $table->string('region',255)->nullable();
            $table->string('shipping_line',255)->nullable();
            $table->string('ed_bl_number',255)->nullable();
            $table->string('container_number',255)->nullable();
            $table->string('courier_tracking',255)->nullable();
            $table->dateTime('etd_origin')->nullable();
            $table->dateTime('atd_origin')->nullable();
            $table->dateTime('eta_destination')->nullable();
            $table->dateTime('ata_destination')->nullable();
            $table->date('delivery_date')->nullable();
            $table->dateTime('date_docs_completed')->nullable();
            $table->text('remarks')->nullable();
            $table->foreign('process_order_id')->references('id')->on('processed_orders')->onDelete('cascade');
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
        Schema::dropIfExists('shipment_details');
    }
}
