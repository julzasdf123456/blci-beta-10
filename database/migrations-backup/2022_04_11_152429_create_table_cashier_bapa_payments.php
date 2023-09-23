<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierBapaPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('Cashier_BAPAPayments')) {
            Schema::create('Cashier_BAPAPayments', function (Blueprint $table) {
                $table->string('id')->unsigned();
                $table->primary('id');
                $table->string('BAPAName')->nullable();
                $table->date('ServicePeriod')->nullable();
                $table->string('ORNumber')->nullable();
                $table->date('ORDate')->nullable();
                $table->string('SubTotal')->nullable();
                $table->string('TwoPercentDiscount')->nullable();
                $table->string('FivePercentDiscount')->nullable();
                $table->string('AdditionalCharges')->nullable();
                $table->string('Deductions')->nullable();
                $table->string('VAT')->nullable();
                $table->string('Total')->nullable();
                $table->string('Teller')->nullable();
                $table->string('NoOfConsumersPaid')->nullable();
                $table->string('Status')->nullable();
                $table->string('Notes')->nullable();
                $table->timestamps();
            });
        }        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Cashier_BAPAPayments');
    }
}
