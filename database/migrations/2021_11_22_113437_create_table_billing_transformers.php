<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingTransformers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_Transformers', function (Blueprint $table) {
            $table->string('id', 120)->unsigned();
            $table->primary('id');
            $table->string('ServiceAccountId', 120)->nullable();
            $table->string('TransformerNumber', 120)->nullable();
            $table->string('Rating', 20)->nullable(); // IN KVA
            $table->string('RentalFee', 30)->nullable();
            $table->string('Load', 50)->nullable(); // IN KWH
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
        Schema::dropIfExists('Billing_Transformers');
    }
}
