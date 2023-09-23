<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmMeterInstallation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_MeterInstallation', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('Type')->nullable();
            $table->string('NewMeterNumber', 300)->nullable();
            $table->string('NewMeterBrand', 300)->nullable();
            $table->string('NewMeterSize', 300)->nullable();
            $table->string('NewMeterType', 300)->nullable();
            $table->string('NewMeterAmperes', 300)->nullable();
            $table->decimal('NewMeterInitialReading', 18, 2)->nullable();
            $table->string('NewMeterLineToNeutral', 300)->nullable();
            $table->string('NewMeterLineToGround', 300)->nullable();
            $table->string('NewMeterNeutralToGround', 300)->nullable();
            $table->date('DateInstalled')->nullable();
            $table->string('NewMeterMultiplier', 300)->nullable();
            $table->string('TransfomerCapacity', 300)->nullable();
            $table->string('TransformerID', 300)->nullable();
            $table->string('PoleID', 300)->nullable();
            $table->string('CTSerialNumber', 300)->nullable();
            $table->string('NewMeterRemarks', 2500)->nullable();
            $table->string('OldMeterNumber', 300)->nullable();
            $table->string('OldMeterBrand', 300)->nullable();
            $table->string('OldMeterSize', 300)->nullable();
            $table->string('OldMeterType', 300)->nullable();
            $table->date('DateRemoved')->nullable();
            $table->string('ReasonForChanging', 1500)->nullable();
            $table->string('OldMeterMultiplier', 300)->nullable();
            $table->string('OldMeterRemarks', 2500)->nullable();
            $table->string('InstalledBy', 500)->nullable();
            $table->string('CheckedBy', 500)->nullable();
            $table->string('Witness', 500)->nullable();
            $table->string('BLCIRepresentative', 500)->nullable();
            $table->string('ApprovedBy', 500)->nullable();
            $table->string('RemovedBy', 500)->nullable();
            $table->string('CustomerSignature')->nullable();
            $table->string('WitnessSignature')->nullable();
            $table->string('InstalledBySignature')->nullable();
            $table->string('ApprovedBySignature')->nullable();
            $table->string('CheckedBySignature')->nullable();
            $table->string('BLCIRepresentativeSignature')->nullable();
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
        Schema::dropIfExists('CRM_MeterInstallation');
    }
}
