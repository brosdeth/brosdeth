<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number',10);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('method_id');
            $table->double('total_sale',12,3)->default(0);
            $table->double('total_discount',12,3)->default(0);
            $table->double('total_received',12,3)->default(0);
            $table->double('total_unreceived',12,3)->default(0);
            $table->double('total_amount',12,3)->default(0);
            $table->double('change_return',12,3)->default(0);
            $table->double('extra_charge',12,3)->default(0);
            $table->datetime('sale_date');
            $table->float('rate',6)->default(4000);
            $table->string('desc',255)->nullable();
            $table->tinyInteger('method_type')->nullable()->comment('2 = Cash, 3 = Bank');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade');
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
        Schema::dropIfExists('sales');
    }
}
