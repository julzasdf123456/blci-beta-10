<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCacheCashierOtherPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cache_Cashier_OtherPayments', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->string('TransactionIndexId')->nullable();
            $table->string('Particular', 350)->nullable();
            $table->string('Amount')->nullable();
            $table->string('VAT')->nullable();
            $table->string('Total')->nullable();
            $table->string('AccountCode')->nullable();
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
        Schema::dropIfExists('Cache_Cashier_OtherPayments');
    }
}
