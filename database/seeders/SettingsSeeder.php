<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Itpi\Models\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'password_default' => '12345678',
            'pin_default' => '123456',
            'app_version' => '1.0.0',
            'assets_version' => '1.0'
        ]);
    }
}
