<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingChangeMeterLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_ChangeMeterLogs', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->string('OldMeterSerial')->nullable();
            $table->string('NewMeterSerial')->nullable();
            $table->string('PullOutReading')->nullable();
            $table->string('AdditionalKwhForNextBilling')->nullable();
            $table->string('Status')->nullable();
            $table->string('NewMeterStartKwh')->nullable();
            $table->date('ServicePeriod')->nullable();
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
        Schema::dropIfExists('Billing_ChangeMeterLogs');
    }
}
