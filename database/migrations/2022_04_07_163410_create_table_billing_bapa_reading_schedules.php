<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingBapaReadingSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_BAPAReadingSchedule', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->date('ServicePeriod')->nullable();
            $table->string('Town')->nullable();
            $table->string('BAPAName')->nullable();
            $table->string('Status')->nullable();
            $table->string('DownloadedBy')->nullable();
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
        Schema::dropIfExists('Billing_BAPAReadingSchedule');
    }
}
