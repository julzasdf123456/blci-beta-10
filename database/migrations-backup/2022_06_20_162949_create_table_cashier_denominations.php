<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierDenominations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_Denominations', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('OneThousand')->nullable();
            $table->string('FiveHundred')->nullable();
            $table->string('OneHundred')->nullable();
            $table->string('Fifty')->nullable();
            $table->string('Twenty')->nullable();
            $table->string('Ten')->nullable();
            $table->string('Five')->nullable();
            $table->string('Peso')->nullable();
            $table->string('Cents')->nullable();
            $table->string('PaidBillId')->nullable();
            $table->string('Notes')->nullable();
            $table->string('Total')->nullable();
            $table->date('ORDate')->nullable();
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
        Schema::dropIfExists('Cashier_Denominations');
    }
}
