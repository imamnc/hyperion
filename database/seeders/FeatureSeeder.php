<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Itpi\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // VMS
        Feature::create([
            'name' => 'Manajemen Vendor',
            'code' => 'vms'
        ]);
        // Blacklist
        Feature::create([
            'name' => 'Manajemen Blacklist',
            'code' => 'blacklist'
        ]);
        // Pengadaan
        Feature::create([
            'name' => 'Manajemen Pengadaan',
            'code' => 'procurement'
        ]);
        // Kontrak
        Feature::create([
            'name' => 'Manajaemen Kontrak',
            'code' => 'contract'
        ]);
        // Purchase Requisition
        Feature::create([
            'name' => 'Purchase Requisition',
            'code' => 'pr'
        ]);
    }
}
