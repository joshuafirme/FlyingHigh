<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionLineItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_line_items', function (Blueprint $table) {
            $table->id();
            $table->string('orderNumber');
            $table->string('orderType')->nullable();
            $table->string('lineNumber');
            $table->string('itemNumber');
            $table->integer('qtyRcdGood');
            $table->integer('qtyRcdBad');
            $table->string('billOfLading');
            $table->string('rcvComments');
            $table->string('palletId')->nullable();
            $table->string('unitOfMeasure');
            $table->string('location');
            $table->string('lotNumber')->nullable();
            $table->string('receiptDate');
            $table->string('lotExpiration')->nullable();
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
        Schema::dropIfExists('transaction_line_items');
    }
}
