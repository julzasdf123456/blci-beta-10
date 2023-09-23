<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableKatasNgVat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_KatasNgVat', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->string('AccountNumber')->nullable();
            $table->string('Balance')->nullable();
            $table->string('SeriesNo')->nullable();
            $table->string('Notes', 600)->nullable();
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
        Schema::dropIfExists('Billing_KatasNgVat');
    }
}
