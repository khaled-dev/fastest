<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('place_id')->index();
            $table->double('lat');
            $table->double('lng');
            $table->string('formatted_address');
            $table->string('business_status');
            $table->string('name');
            $table->string('icon');
            $table->integer('rating');
            $table->integer('user_ratings_total');
            $table->timestamps();

            $table->index(['lat', 'lng'], 'lat_lng_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
