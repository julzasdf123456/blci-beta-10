<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_Banks', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('BankFullName', 500)->nullable();
            $table->string('BankAbbrev')->nullable();
            $table->string('Address', 1000)->nullable();
            $table->string('TIN')->nullable();
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
        Schema::dropIfExists('Cashier_Banks');
    }
}
