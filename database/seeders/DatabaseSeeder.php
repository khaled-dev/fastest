<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CitySeeder::class,
            SuperAdmin::class,
            BankSeeder::class,
            NormalAdmin::class,
            CarTypeSeeder::class,
            TerritorySeeder::class,
            TerritorySeeder::class,
            NationalitySeeder::class,
        ]);
    }
}
