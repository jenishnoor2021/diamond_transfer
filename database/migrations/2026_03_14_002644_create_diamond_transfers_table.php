<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiamondTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamond_transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('diamond_id')->constrained()->cascadeOnDelete();

            $table->string('from_location');
            $table->string('to_location');

            $table->date('transfer_date');

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
        Schema::dropIfExists('diamond_transfers');
    }
}
