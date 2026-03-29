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
            $table->id();
            $table->foreignId('diamond_id');
            $table->foreignId('broker_id')->nullable();
            $table->foreignId('sold_by_user_id'); // who sold
            $table->decimal('price', 12, 2);
            $table->string('payment_status'); // paid / partial / pending
            $table->string('payment_type'); // cash / bank / upi
            $table->date('sold_date');
            $table->string('sale_type'); // broker / direct
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
