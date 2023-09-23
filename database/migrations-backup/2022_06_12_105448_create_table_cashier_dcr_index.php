<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierDcrIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_DCRIndex', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('GLCode')->nullable();
            $table->string('NEACode')->nullable();
            $table->string('TableName')->nullable();
            $table->string('Columns', 1000)->nullable();
            $table->string('TownCode')->nullable();
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
        Schema::dropIfExists('Cashier_DCRIndex');
    }
}
