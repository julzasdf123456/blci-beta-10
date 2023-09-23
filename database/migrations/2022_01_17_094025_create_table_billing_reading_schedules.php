<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingReadingSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_ReadingSchedules', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AreaCode')->nullable();
            $table->string('GroupCode')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->date('ScheduledDate')->nullable();
            $table->string('MeterReader')->nullable();
            $table->string('Status')->nullable(); // Downloaded,Null
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
        Schema::dropIfExists('Billing_ReadingSchedules');
    }
}
