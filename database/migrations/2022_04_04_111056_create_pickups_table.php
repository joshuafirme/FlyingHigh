<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id();
            $table->string('shipmentId');
            $table->string('customerEmail');
            $table->string('custId');
            $table->string('custName');
            $table->string('shipPhone');
            $table->string('shipName');
            $table->string('shipAddr1')->nullable();
            $table->string('shipAddr2')->nullable();
            $table->string('shipAddr3')->nullable();
            $table->string('shipAddr4')->nullable();
            $table->string('shipCity')->nullable();
            $table->string('shipState')->nullable();
            $table->string('shipZip')->nullable();
            $table->string('shipCountryIso');
            $table->string('shipMethod');
            $table->string('shipCarrier')->nullable();
            $table->string('batchId');
            $table->string('contractDate');
            $table->string('orderId');
            $table->string('govInvoiceNumber')->nullable();
            $table->string('dateTimeSubmittedIso');
            $table->double('shippingChargeAmount');
            $table->string('customerTIN')->nullable();
            $table->double('salesTaxAmount');
            $table->double('shippingTaxTotalAmount');
            $table->double('packageTotal');
            $table->string('orderSource')->nullable();
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
        Schema::dropIfExists('pickups');
    }
}
