<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->text('description');
            $table->double('min_offer_price');
            $table->double('max_offer_price');
            $table->enum('state', [
                'opened',
                'under_negotiation',
                'in_progress',
                'completed',
                'canceled'
            ])->default('opened')->index();
            $table->timestamps();

            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('set null');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
