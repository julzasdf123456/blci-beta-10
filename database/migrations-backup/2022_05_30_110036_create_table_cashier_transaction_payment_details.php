<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierTransactionPaymentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_TransactionPaymentDetails', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('TransactionIndexId')->nullable();
            $table->string('Amount')->nullable();
            $table->string('PaymentUsed')->nullable();
            $table->string('Bank')->nullable();
            $table->string('CheckNo')->nullable();
            $table->date('CheckExpiration')->nullable();
            $table->string('ORNumber')->nullable();
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
        Schema::dropIfExists('Cashier_TransactionPaymentDetails');
    }
}
