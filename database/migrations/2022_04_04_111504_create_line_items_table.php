<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->string('lineNumber');
            $table->string('orderId');
            $table->string('partNumber');
            $table->integer('quantity');
            $table->string('name');
            $table->string('lineType');
            $table->string('parentKitItem');
            $table->string('remarks')->nullable();
            $table->double('pv');
            $table->double('itemUnitPrice');
            $table->double('itemExtendedPrice');
            $table->double('salesPrice');
            $table->double('taxableAmount');
            $table->double('lineItemTotal');
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
        Schema::dropIfExists('line_items');
    }
}
