<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicedatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicedatas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->unsigned()->index();
            $table->foreignId('diamond_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight', 10, 3)->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->decimal('gst', 5, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoicedatas');
    }
}
