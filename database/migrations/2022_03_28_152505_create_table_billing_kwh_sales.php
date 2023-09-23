<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingKwhSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_KwhSales', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->date('ServicePeriod')->nullable();
            $table->string('Town')->nullable();
            $table->string('BilledKwh')->nullable(); // FROM SUPPLIER
            $table->string('ConsumedKwh')->nullable(); // FROM BILLS
            $table->string('NoOfConsumers')->nullable();
            $table->string('Notes', 500)->nullable();
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
        Schema::dropIfExists('Billing_KwhSales');
    }
}
