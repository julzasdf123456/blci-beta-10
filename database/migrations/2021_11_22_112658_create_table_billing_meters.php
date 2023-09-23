<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingMeters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_Meters', function (Blueprint $table) {
            $table->string('id', 120)->unsigned(); 
            $table->primary('id');
            $table->string('ServiceAccountId', 120)->nullable();
            $table->string('SerialNumber', 100)->nullable();
            $table->string('SealNumber', 120)->nullable();
            $table->string('Brand', 180)->nullable();
            $table->string('Model', 180)->nullable();
            $table->string('Multiplier', 10)->nullable();
            $table->string('Status', 60)->nullable();
            $table->date('ConnectionDate')->nullable();
            $table->datetime('LatestReadingDate')->nullable();
            $table->date('DateDisconnected')->nullable();
            $table->date('DateTransfered')->nullable();
            $table->string('InitialReading', 30)->nullable();
            $table->string('LatestReading', 30)->nullable();
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
        Schema::dropIfExists('Billing_Meters');
    }
}
