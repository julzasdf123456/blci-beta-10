<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBillingKatasNgVatTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_KatasNgVatTotal', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Balance')->nullable();
            $table->string('SeriesNo')->nullable();
            $table->string('Description', 500)->nullable();
            $table->string('Year')->nullable();
            $table->string('UserId')->nullable();
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
        Schema::dropIfExists('Billing_KatasNgVatTotal');
    }
}
