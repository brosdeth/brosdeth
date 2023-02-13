<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bill_pay_id');
            $table->unsignedBigInteger('method_id');
            $table->morphs('paymentable');
            $table->double('amount',12,2)->default(0.00)->nullable();
            $table->double('paid_amount',12,2)->default(0.00)->nullable();
            $table->datetime('payment_date');
            $table->foreign('bill_pay_id')->references('id')->on('bill_payments')->onUpdate('cascade');
            $table->foreign('method_id')->references('id')->on('payment_methods')->onUpdate('cascade');
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
        Schema::dropIfExists('bill_payment_details');
    }
}
