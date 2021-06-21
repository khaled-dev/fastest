<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStateFieldInOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE offers MODIFY COLUMN `state`
            enum('under_negotiation', 'rejected', 'accepted', 'completed', 'canceled')
            DEFAULT 'under_negotiation'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            ALTER TABLE offers MODIFY COLUMN `state`
            enum('under_negotiation', 'accepted', 'completed', 'canceled')
            DEFAULT 'under_negotiation'
        ");
    }
}
