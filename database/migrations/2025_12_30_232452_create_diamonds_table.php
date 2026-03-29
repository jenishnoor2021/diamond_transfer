<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiamondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamonds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->nullable()->constrained('locations');
            $table->string('barcode_number');
            $table->string('stock_no');
            $table->string('certificate_no')->nullable();
            $table->string('availability')->nullable();
            $table->string('shape')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('color')->nullable();
            $table->string('clarity')->nullable();
            $table->string('fancy_color')->nullable();
            $table->string('fancy_color_intensity')->nullable();
            $table->string('fancy_color_overtone')->nullable();
            $table->string('cut_grade')->nullable();
            $table->string('polish')->nullable();
            $table->string('symmetry')->nullable();
            $table->string('fluorescence_intensity')->nullable();
            $table->string('measurements')->nullable();
            $table->string('depth_percent')->nullable();
            $table->string('table_percent')->nullable();
            $table->string('lab')->nullable();
            $table->decimal('price_per_carat', 10, 2)->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('image_url')->nullable();
            $table->text('video_url')->nullable();
            $table->string('growth_type')->nullable();
            $table->string('status')->nullable();

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
        Schema::dropIfExists('diamonds');
    }
}
