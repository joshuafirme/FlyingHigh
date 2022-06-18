<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->integer('orderNumber');
            $table->string('orderType');
            $table->string('orderDate');
            $table->integer('vendorNo');
            $table->string('vendorName')->nullable();
            $table->string('shipFromAddress')->nullable();
            $table->string('shipFromCountry')->nullable();
            $table->string('transactionReferenceNumber');
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
        Schema::dropIfExists('purchase_order');
    }
}
