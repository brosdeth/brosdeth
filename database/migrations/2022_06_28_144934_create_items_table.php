<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index(['category_id', 'item_name']);
            $table->unsignedBigInteger('category_id');
            $table->string('item_code', 8)->uniqid();
            $table->string('barcode', 13)->nullable()->uniqid();
            $table->string('item_name', 100);
            $table->string('unit_name', 100)->nullable();
            $table->double('price', 12, 2)->default(0);
            $table->double('cost', 12, 2)->default(0);
            $table->string('image', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->tinyInteger('is_expired')->default(1)->comment('1 = Not Expired Date, 2 = Expired Date');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate("cascade")->onDelete("cascade");
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
        Schema::dropIfExists('items');
    }
}
