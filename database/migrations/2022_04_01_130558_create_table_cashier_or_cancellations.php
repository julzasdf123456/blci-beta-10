<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierOrCancellations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_ORCancellations', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ORNumber')->nullable();
            $table->date('ORDate')->nullable();
            $table->string('From')->nullable(); // PaidBills, Transactions
            $table->string('ObjectId')->nullable();
            $table->datetime('DateTimeFiled')->nullable();
            $table->datetime('DateTimeApproved')->nullable();
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
        Schema::dropIfExists('Cashier_ORCancellations');
    }
}
