<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index(['stock_id']);
            $table->tinyInteger('stock_type')->nullable()->comment('1/បើកស្តុកថ្មី 2/ទិញចូល 3/លក់ចេញ 4/បន្ថែមស្តុក 5/ដកស្តុក 6/ដកចំនួនបំបែកមេ 7/បន្ថែមចំនួនបំបែកកូន');
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->integer('opening_stock')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('balance_qty')->default(0);
            $table->string('batch_code',8)->nullable()->comment('code expired date');
            $table->date('expired_date')->nullable();
            $table->foreign('stock_id')->references('id')->on('item_stocks')->onUpdate('cascade');
            $table->morphs('transactions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transactions');
    }
}
