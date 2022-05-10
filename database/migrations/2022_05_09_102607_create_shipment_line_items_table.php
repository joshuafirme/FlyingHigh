<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_line_items', function (Blueprint $table) {
            $table->id();
            $table->string("orderId");
            $table->string("shipmentId");
            $table->string("orderLineNumber");
            $table->string("partNumber");
            $table->string("trackingNo");
            $table->string("qtyOrdered");
            $table->string("qtyShipped");
            $table->string("reasonCode");
            $table->string("shipDateTime");
            $table->string("lotNumber");
            $table->tinyInteger("status")->default(0);
            $table->integer('qty_returned')->default(0);
            $table->string('rma_number')->nullable();
            $table->string('return_reason')->nullable();
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
        Schema::dropIfExists('shipment_line_items');
    }
}
