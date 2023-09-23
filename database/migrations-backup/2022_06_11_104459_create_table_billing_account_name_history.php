<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingAccountNameHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_AccountNameHistory', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('AccountNumber')->nullable();
            $table->string('OldAccountName', 600)->nullable();
            $table->string('Notes', 1000)->nullable();
            $table->string('UserId')->nullable();
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
        Schema::dropIfExists('Billing_AccountNameHistory');
    }
}
