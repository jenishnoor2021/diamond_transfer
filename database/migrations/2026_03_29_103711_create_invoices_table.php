<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('serise_no')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_state')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_mobile')->nullable();
            $table->string('client_gst_no')->nullable();
            $table->string('client_gst_name')->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->string('discount_type')->default('fix');
            $table->decimal('grand_total', 10, 2)->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
