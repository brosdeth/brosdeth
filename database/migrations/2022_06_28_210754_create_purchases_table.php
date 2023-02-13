<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index('sup_id');
            $table->string('invoice_number',10);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('sup_id');
            $table->double('total_cost',12,2)->default(0.00);
            $table->double('extra_charge',12,2)->default(0.00);
            $table->double('total_discount',12,2)->default(0.00);
            $table->double('total_paid',12,2)->default(0.00);
            $table->datetime('purchase_date');
            $table->string('desc')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = processing, 2 = received = 3 Done');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('sup_id')->references('id')->on('suppliers')->onUpdate('cascade');
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
        Schema::dropIfExists('purchases');
    }
}
