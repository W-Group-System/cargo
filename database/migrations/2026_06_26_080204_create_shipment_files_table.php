<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('processed_order_id');
            $table->string('file_name',255);
            $table->string('shipment_status',10)->nullable();
            $table->string('file_type',100)->nullable();
            $table->string('file_ext',10)->nullable();
            $table->string('file_path',255);
            $table->integer('user_id');
            $table->foreign('processed_order_id')->references('id')->on('processed_orders')->onDelete('cascade');
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
        Schema::dropIfExists('shipment_files');
    }
}
