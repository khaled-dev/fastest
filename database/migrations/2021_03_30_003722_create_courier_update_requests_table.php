<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierUpdateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_update_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('courier_id')->unique();

            $table->unsignedBigInteger('territory_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('car_type_id')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();

            $table->string('name')->nullable();
            $table->string('national_number')->unique()->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('car_number')->nullable();
            $table->string('iban_number')->nullable();

            $table->foreign('courier_id')
                ->references('id')
                ->on('couriers')
                ->onDelete('cascade');

            $table->foreign('territory_id')
                ->references('id')
                ->on('territories')
                ->onDelete('set null');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('set null');

            $table->foreign('car_type_id')
                ->references('id')
                ->on('car_types')
                ->onDelete('set null');

            $table->foreign('nationality_id')
                ->references('id')
                ->on('nationalities')
                ->onDelete('set null');

            $table->foreign('bank_id')
                ->references('id')
                ->on('banks')
                ->onDelete('set null');

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
        Schema::dropIfExists('courier_update_requests');
    }
}
