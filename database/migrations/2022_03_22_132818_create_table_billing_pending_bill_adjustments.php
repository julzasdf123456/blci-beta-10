<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingPendingBillAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_PendingBillAdjustments', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ReadingId')->nullable();
            $table->string('KwhUsed')->nullable();
            $table->string('AccountNumber')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('Confirmed')->nullable(); // Yes, No = null
            $table->date('ReadDate')->nullable();
            $table->string('UserId')->nullable();
            $table->string('Office')->nullable();
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
        Schema::dropIfExists('Billing_PendingBillAdjustments');
    }
}
