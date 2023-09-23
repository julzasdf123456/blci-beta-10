<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMSpanningData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_SpanninData', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('PrimarySpan', 15)->nullable();
            $table->string('PrimarySize', 10)->nullable();
            $table->string('PrimaryType', 15)->nullable();
            $table->string('NeutralSpan', 15)->nullable();
            $table->string('NeutralSize', 10)->nullable();
            $table->string('NeutralType', 15)->nullable();
            $table->string('SecondarySpan', 15)->nullable();
            $table->string('SecondarySize', 10)->nullable();
            $table->string('SecondaryType', 15)->nullable();
            $table->string('SDWSpan', 15)->nullable();
            $table->string('SDWSize', 10)->nullable();
            $table->string('SDWType', 15)->nullable();
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
        Schema::dropIfExists('CRM_SpanninData');
    }
}
