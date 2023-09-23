<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierOrAssigning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_ORAssigning', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ORNumber')->nullable();
            $table->string('UserId')->nullable();
            $table->date('DateAssigned')->nullable();
            $table->string('IsSetManually')->nullable(); // Yes, No = null
            $table->time('TimeAssigned')->nullable();
            $table->string('Office')->nullable();
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
        Schema::dropIfExists('Cashier_ORAssigning');
    }
}
