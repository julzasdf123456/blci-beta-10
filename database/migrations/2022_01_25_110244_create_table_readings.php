<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReadings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_Readings', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->datetime('ReadingTimestamp')->nullable();
            $table->string('KwhUsed')->nullable();
            $table->string('DemandKwhUsed')->nullable();
            $table->string('Notes', 3000)->nullable();
            $table->string('Latitude', 60)->nullable();
            $table->string('Longitude', 60)->nullable();
            $table->string('FieldStatus', 50)->nullable();
            $table->string('MeterReader', 60)->nullable();
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
        Schema::dropIfExists('Billing_Readings');
    }
}
