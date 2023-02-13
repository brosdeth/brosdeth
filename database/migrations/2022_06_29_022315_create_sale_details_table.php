<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index('stock_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('stock_id');
            $table->integer('qty');
            $table->double('cost',12,3)->default(0.000);
            $table->double('price',12,3)->default(0.000);
            $table->double('discount');
            $table->tinyInteger('discount_type')->default(1)->comment('1 = amount, 2 = percent');
            $table->tinyInteger('is_expired')->default(1)->comment('1 = Not Expired Date, 2 = Expired Date');
            $table->foreign('sale_id')->references('id')->on('sales')->onUpdate('cascade');
            $table->foreign('stock_id')->references('id')->on('item_stocks')->onUpdate('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('sale_details');
    }
}
