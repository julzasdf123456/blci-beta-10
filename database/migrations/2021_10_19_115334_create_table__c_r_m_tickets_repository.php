<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMTicketsRepository extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_TicketsRepository', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Name', 600)->nullable();
            $table->string('Description', 1000)->nullable();
            $table->string('ParentTicket')->nullable();
            $table->string('Type')->nullable(); // Complain, Request, etc.
            $table->string('KPSCategory')->nullable(); // 1-7
            $table->string('KPSIssue')->nullable(); // What year the KPS is issued, or name of issuance code
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
        Schema::dropIfExists('CRM_TicketsRepository');
    }
}
