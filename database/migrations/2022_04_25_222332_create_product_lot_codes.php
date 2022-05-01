<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLotCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_lot_codes', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('lot_code');
            $table->integer('stock');
            $table->string('expiration');

            // JDE lot code = 1 Supplier lot code = 2
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(1);

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
        Schema::dropIfExists('product_lot_codes');
    }
}
