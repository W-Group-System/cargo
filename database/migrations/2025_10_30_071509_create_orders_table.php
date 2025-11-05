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
            $table->id();
            $table->string('sap_server'); // whi, pbi, or ccc
            $table->string('DocNum');     // SO Number
            $table->string('CardCode');
            $table->string('CardName');
            $table->string('U_Label')->nullable();
            $table->string('U_Packaging')->nullable();
            $table->timestamps();

            $table->unique(['sap_server', 'DocNum']); // prevent duplicates
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
