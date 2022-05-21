<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_no');
            $table->string('sku');
            $table->string('description');
            $table->string('lot_code')->nullable();
            $table->string('external_line_no');
            $table->string('uom');
            $table->integer('qty_order');
            $table->integer('qty_received')->default(0);
            $table->string('order_date')->nullable();
            $table->string('delivery_date')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('stock_transfer');
    }
}
