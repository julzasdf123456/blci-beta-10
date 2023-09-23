<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMServiceConnectionMeterAndTransformer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_ServiceConnectionMeterAndTransformer', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId');
            $table->string('MeterSerialNumber', 150)->nullable();
            $table->string('MeterBrand', 200)->nullable();
            $table->string('MeterSealNumber', 200)->nullable();
            $table->string('MeterKwhStart', 30)->nullable();
            $table->string('MeterEnclosureType', 150)->nullable();
            $table->string('MeterHeight', 20)->nullable();
            $table->text('MeterNotes', 2000)->nullable();
            $table->string('TypeOfMetering', 100)->nullable();
            $table->string('DirectRatedCapacity', 50)->nullable();
            $table->string('InstrumentRatedCapacity', 50)->nullable();
            $table->string('InstrumentRatedLineType', 50)->nullable();
            $table->string('CTPhaseA', 50)->nullable();
            $table->string('CTPhaseB', 50)->nullable();
            $table->string('CTPhaseC', 50)->nullable();
            $table->string('PTPhaseA', 50)->nullable();
            $table->string('PTPhaseB', 50)->nullable();
            $table->string('PTPhaseC', 50)->nullable();
            $table->string('BrandPhaseA', 150)->nullable();
            $table->string('BrandPhaseB', 150)->nullable();
            $table->string('BrandPhaseC', 150)->nullable();
            $table->string('SNPhaseA', 250)->nullable();
            $table->string('SNPhaseB', 250)->nullable();
            $table->string('SNPhaseC', 250)->nullable();
            $table->string('SecuritySealPhaseA', 250)->nullable();
            $table->string('SecuritySealPhaseB', 250)->nullable();
            $table->string('SecuritySealPhaseC', 250)->nullable();
            $table->string('Phase', 80)->nullable();
            $table->string('TransformerQuantity', 20)->nullable();
            $table->string('TransformerNumber', 150)->nullable();
            $table->string('TransformerRating', 150)->nullable();
            $table->string('TransformerOwnershipType', 150)->nullable();
            $table->string('TransformerOwnership', 150)->nullable();
            $table->string('TransformerBrand', 150)->nullable();
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
        Schema::dropIfExists('CRM_ServiceConnectionMeterAndTransformer');
    }
}
