<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierBapaAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_BAPAAdjustments', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('BAPAName', 500)->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('DiscountPercentage')->nullable();
            $table->string('DiscountAmount')->nullable();
            $table->string('NumberOfConsumers')->nullable();
            $table->string('SubTotal')->nullable();
            $table->string('NetAmount')->nullable();
            $table->string('UserId')->nullable();
            $table->string('Route')->nullable();
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
        Schema::dropIfExists('Cashier_BAPAAdjustments');
    }
}
