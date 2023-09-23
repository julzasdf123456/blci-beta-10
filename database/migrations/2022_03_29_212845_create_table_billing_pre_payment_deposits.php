<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingPrePaymentDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_PrePaymentBalance', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->string('Balance')->nullable();
            $table->string('Status')->nullable();
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
        Schema::dropIfExists('Billing_PrePaymentBalance');
    }
}
