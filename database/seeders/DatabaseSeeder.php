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
        $this->call(SettingsSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(UserSeeder::class);
    }
}
