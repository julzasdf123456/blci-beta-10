<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingMeterReaders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_MeterReaders', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('MeterReaderCode', 30)->nullable();
            $table->string('UserId', 50)->nullable();
            $table->string('DeviceMacAddress', 60)->nullable();
            $table->string('AreaCodeAssignment', 20)->nullable();
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
        Schema::dropIfExists('Billing_MeterReaders');
    }
}
