<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReportsDistributionSystemLoss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reports_DistributionSystemLoss', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->date('ServicePeriod')->nullable();
            $table->string('CalatravaSubstation')->nullable();
            $table->string('VictoriasSubstation')->nullable();
            $table->string('SagaySubstation')->nullable();
            $table->string('SanCarlosSubstation')->nullable();
            $table->string('EscalanteSubstation')->nullable();
            $table->string('LopezSubstation')->nullable();
            $table->string('CadizSubstation')->nullable();
            $table->string('IpiSubstation')->nullable();
            $table->string('TobosoCalatravaSubstation')->nullable();
            $table->string('VictoriasMillingCompany')->nullable();
            $table->string('SanCarlosBionergy')->nullable();
            $table->string('TotalEnergyInput')->nullable();
            $table->string('EnergySales')->nullable();
            $table->string('EnergyAdjustmentRecoveries')->nullable();
            $table->string('TotalEnergyOutput')->nullable();    
            $table->string('TotalSystemLoss')->nullable();
            $table->string('TotalSystemLossPercentage')->nullable();
            $table->string('UserId')->nullable();
            $table->date('From')->nullable();
            $table->date('To')->nullable();
            $table->string('Status')->nullable();
            $table->string('Notes', 1000)->nullable();
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
        Schema::dropIfExists('Reports_DistributionSystemLoss');
    }
}
