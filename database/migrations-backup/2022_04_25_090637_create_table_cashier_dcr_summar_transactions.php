<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierDcrSummarTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_DCRSummaryTransactions', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('GLCode')->nullable();
            $table->string('NEACode')->nullable();
            $table->string('Description')->nullable();
            $table->string('Amount')->nullable();
            $table->date('Day')->nullable();
            $table->time('Time')->nullable();
            $table->string('Teller')->nullable();
            $table->string('DCRNumber')->nullable();
            $table->string('Status')->nullable();
            $table->string('ORNumber')->nullable();
            $table->string('ReportDestination')->nullable(); // SALES, COLLECTION, BOTH
            $table->string('Office')->nullable();
            $table->string('AccountNumber')->nullable();
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
        Schema::dropIfExists('Cashier_DCRSummaryTransactions');
    }
}
