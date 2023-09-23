<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMServiceConnectionCrew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_ServiceConnectionCrew', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('StationName', 140)->nullable();
            $table->string('CrewLeader', 300)->nullable();
            $table->string('Members', 1500)->nullable();
            $table->string('Notes', 1000)->nullable();
            $table->string('Office')->nullable();
            $table->string('Grouping')->nullable(); // Group, Individual
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
        Schema::dropIfExists('CRM_ServiceConnectionCrew');
    }
}
