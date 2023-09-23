<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingMeterReaderTracks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_MeterReaderTracks', function (Blueprint $table) {
            $table->string('id', 100)->unsigned();
            $table->primary('id');
            $table->string('TrackNameId', 100)->nullable();
            $table->string('Latitude', 60)->nullable();
            $table->string('Longitude', 60)->nullable();
            $table->datetime('Captured')->nullable();
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
        Schema::dropIfExists('Billing_MeterReaderTracks');
    }
}
