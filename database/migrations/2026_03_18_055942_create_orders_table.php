<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_order_id');
            $table->string('sap_server'); // whi, pbi, or ccc
            $table->string('DocNum');     // SO Number
            $table->string('CardCode');
            $table->string('CardName');
            $table->string('Label')->nullable();
            $table->string('Packaging')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
