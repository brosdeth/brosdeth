<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('item_split_id')->nullable();
            $table->tinyInteger('item_split_key')->nullable();
            $table->string('unit_name', 150)->nullable();
            $table->json('units')->nullable();
            $table->json('attrs')->comment('attribute product')->nullable();
            $table->string('code', 8)->uniqid();
            $table->string('barcode', 13)->nullable();
            $table->double('price', 12, 2);
            $table->double('cost', 12, 2);
            $table->integer('divide')->default(1);
            $table->tinyInteger('have_attr')->default(1)->comment('1 = Don\'t have, 2 = Have');
            $table->tinyInteger('is_expired')->default(1)->comment('1 = Not Expired Date, 2 = Expired Date');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate("cascade")->onDelete("cascade");
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
        Schema::dropIfExists('item_stocks');
    }
}
