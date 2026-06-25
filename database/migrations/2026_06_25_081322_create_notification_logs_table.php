<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipment_details_id');
            $table->string('template_code');
            $table->string('user_id');
            $table->string('subject',200);
            $table->text('content');
            $table->char('is_sent',1)->default('1');
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
        Schema::dropIfExists('notification_logs');
    }
}
