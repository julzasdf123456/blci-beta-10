<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingMeterReaderTracknames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_MeterReaderTrackNames', function (Blueprint $table) {
            $table->string('id', 100)->unsigned();
            $table->primary('id');
            $table->string('TrackName', 600)->unsigned();
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
        Schema::dropIfExists('Billing_MeterReaderTrackNames');
    }
}
