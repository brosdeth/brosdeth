<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index(['stock_id']);
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('stock_id');
            $table->integer('qty')->default(0);
            $table->integer('receive_qty')->default(0);
            $table->double('cost',12,3)->default(0.00);
            $table->double('price',12,3)->default(0.00);
            $table->double('discount',12,3)->default(0.00);
            $table->string('batch_code',8)->unique()->nullable()->comment('code expired date');
            $table->date('expired_date')->nullable();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('cascade');
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
        Schema::dropIfExists('purchase_details');
    }
}
