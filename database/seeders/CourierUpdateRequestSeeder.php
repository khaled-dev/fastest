<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourierUpdateRequest;

class CourierUpdateRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourierUpdateRequest::factory(5)->create();
    }
}
