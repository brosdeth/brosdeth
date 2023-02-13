<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsByExpiredDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details_by_expired_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_detail_id');
            $table->string('batch_code',8)->nullable()->comment('code expired date');
            $table->date('expired_date')->nullable();
            $table->integer('qty');
            $table->foreign('sale_detail_id')->references('id')->on('sale_details')->onUpdate('cascade');
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
        Schema::dropIfExists('sale_details_by_expired_dates');
    }
}
