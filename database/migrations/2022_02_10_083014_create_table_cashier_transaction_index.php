<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashierTransactionIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cashier_TransactionIndex', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('TransactionNumber')->nullable();
            $table->string('PaymentTitle', 500)->nullable();
            $table->string('PaymentDetails', 2000)->nullable();
            $table->string('ORNumber')->nullable();
            $table->date('ORDate')->nullable();
            $table->string('SubTotal')->nullable();
            $table->string('VAT')->nullable();
            $table->string('Total')->nullable();
            $table->string('Notes', 1500)->nullable();
            $table->string('UserId')->nullable();
            $table->string('ServiceConnectionId')->nullable();
            $table->string('TicketId')->nullable();
            $table->string('ObjectId')->nullable(); // OTHER PAYABLES
            $table->string('Source')->nullable(); // ServiceConnection, Tickets, Others
            $table->string('PaymentUsed')->nullable(); // Cash, Check, Debit/Credit Card
            $table->string('Status')->nullable();
            $table->string('FiledBy')->nullable();
            $table->string('ApprovedBy')->nullable();
            $table->string('AuditedBy')->nullable();
            $table->string('CancellationNotes')->nullable();
            $table->string('PayeeName')->nullable();
            $table->string('CheckNo')->nullable();
            $table->string('Bank')->nullable();
            $table->date('CheckExpiration')->nullable();
            $table->string('AccountNumber')->nullable();
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
        Schema::dropIfExists('Cashier_TransactionIndex');
    }
}
