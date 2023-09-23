<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierPaidbillsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_PaidBillsDetails', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('BillId')->nullable();
            $table->string('ORNumber')->nullable();
            $table->string('Amount')->nullable();
            $table->string('PaymentUsed')->nullable();
            $table->string('CheckNo')->nullable();
            $table->string('Bank')->nullable();
            $table->date('CheckExpiration')->nullable();
            $table->string('UserId')->nullable();
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
        Schema::dropIfExists('Cashier_PaidBillsDetails');
    }
}
