<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoLineItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_line_items', function (Blueprint $table) {
            $table->id();
            $table->integer('orderNumber');
            $table->string('transactionAction')->nullable();
            $table->decimal('lineNumber', 5, 1);
            $table->string('itemNumber');
            $table->string('quantityOrdered', 6, 1);
            $table->string('quantityOpen', 6, 1);
            $table->string('shipDate')->nullable();
            $table->string('unitOfMeasure');
            $table->string('location')->nullable();
            $table->string('lotNumber')->nullable();
            $table->string('countryOfOrigin')->nullable();
            $table->string('expectedDate')->nullable();
            $table->string('description');
            $table->integer('extWeight');
            $table->string('wtUom');
            $table->string('holdCode')->nullable();
            $table->string('vendorLotNo')->nullable();
            $table->string('lotExp')->nullable();
            $table->string('iOfLading')->nullable();
            $table->string('shipMethod')->nullable();
            $table->string('carrierName')->nullable();
            $table->string('palletId')->nullable();
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
        Schema::dropIfExists('po_line_items');
    }
}
