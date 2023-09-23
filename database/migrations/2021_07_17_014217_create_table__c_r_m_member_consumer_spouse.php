<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMMemberConsumerSpouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_MemberConsumerSpouse', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('MemberConsumerId', 300)->nullable();
            $table->string('FirstName', 300)->nullable();
            $table->string('MiddleName', 300)->nullable();
            $table->string('LastName', 300)->nullable();
            $table->string('Suffix', 50)->nullable();
            $table->string('Gender', 50)->nullable();
            $table->date('Birthdate')->nullable();
            $table->string('Sitio', 1000)->nullable();
            $table->string('Barangay', 50)->nullable();
            $table->string('Town', 50)->nullable();
            $table->string('ContactNumbers', 300)->nullable();
            $table->string('EmailAddress', 300)->nullable();
            $table->string('Religion')->nullable();
            $table->string('Citizenship')->nullable();
            $table->string('Notes', 2000)->nullable();
            $table->string('Trashed', 5)->nullable();
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
        Schema::dropIfExists('CRM_MemberConsumerSpouse');
    }
}
