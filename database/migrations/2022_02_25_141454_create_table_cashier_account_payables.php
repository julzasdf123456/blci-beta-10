<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierAccountPayables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_AccountPayables', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountCode')->nullable();
            $table->string('AccountTitle', 600)->nullable();
            $table->string('AccountDescription', 700)->nullable();
            $table->string('DefaultAmount')->nullable();
            $table->string('VATPercentage')->nullable();
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
        Schema::dropIfExists('Cashier_AccountPayables');
    }
}
