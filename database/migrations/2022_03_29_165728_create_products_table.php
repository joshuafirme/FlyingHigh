<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("itemNumber");
            $table->string("lotCode")->nullable();
            $table->integer("bufferStock")->default(0);
            $table->string("actionCode", 5)->nullable();
            $table->string("baseUOM", 5)->nullable();
            $table->decimal("conversionFactor");
            $table->decimal("height");
            $table->decimal("width");
            $table->decimal("depth");
            $table->string("itemDimensionUnit", 5)->nullable();
            $table->decimal("weight");
            $table->string("weightUnit", 5)->nullable();
            $table->string("volume")->nullable();
            $table->string("volumeUom", 5)->nullable();
            $table->string("productDescription");
            $table->string("harmonizedCode")->nullable();
            $table->string("hazardous", 5)->nullable();
            $table->string("food", 5)->nullable();
            $table->string("refrigerated", 5)->nullable();
            $table->decimal("retailPrice");
            $table->string("willMelt", 5)->nullable();
            $table->string("willFreeze", 5)->nullable();
            $table->string("specialShippingCode")->nullable();
            $table->string("isBarcoded", 5)->nullable();
            $table->string("barCodeNumber")->nullable();
            $table->string("currencyCode", 5)->nullable();
            $table->string("lineType", 5)->nullable();
            $table->string("unHazardCode")->nullable();
            $table->string("isLotControlled", 5)->nullable();
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
        Schema::dropIfExists('products');
    }
}
