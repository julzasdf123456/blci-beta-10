<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingAccountLocationHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_AccountLocationHistory', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->string('Town')->nullable();
            $table->string('Barangay')->nullable();
            $table->string('Purok')->nullable();
            $table->string('AreaCode')->nullable();
            $table->string('SequenceCode')->nullable();
            $table->string('MeterReader')->nullable();
            $table->string('ServiceConnectionId')->nullable();
            $table->date('RelocationDate')->nullable();
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
        Schema::dropIfExists('Billing_AccountLocationHistory');
    }
}
