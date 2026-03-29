<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerMemoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_memo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_memo_id');
            $table->foreignId('diamond_id');
            $table->enum('status', ['issued', 'sold', 'returned'])->default('issued');
            $table->date('returned_at')->nullable();
            $table->date('sold_at')->nullable();
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
        Schema::dropIfExists('broker_memo_items');
    }
}
