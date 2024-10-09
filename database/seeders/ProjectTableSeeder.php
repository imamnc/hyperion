<?php

namespace Database\Seeders;

use Itpi\Models\Project;
use Illuminate\Database\Seeder;
use Itpi\Models\Feature;

class ProjectTableSeeder extends Seeder
{
    protected $data = [
        [
            'name' => 'Kino Indonesia',
            'class' => 'KinoService',
            'code' => 'kino',
            'url' => 'https://demo.itpi.co.id/api/public',
            'key' => 'c44acc2327ef633b4d754dcb69dd07e5'
        ], [
            'name' => 'The Energy',
            'class' => 'TheenergyService',
            'code' => 'tamg',
            'url' => 'https://amg.eprocurement.id/api/public',
            'key' => 'c44acc2327ef633b4d754dcb69dd07e5'
        ], [
            'name' => 'Unioleo',
            'class' => 'UoiService',
            'code' => 'uoi',
            'url' => 'http://redesign.uoi.eprocurement.id/API',
            'key' => 'c44acc2327ef633b4d754dcb69dd07e5'
        ], [
            'name' => 'Mandiri',
            'class' => 'MandiriService',
            'code' => 'mdri',
            'url' => 'https://eprocqa.mandiricoal.co.id/api/public',
            'key' => 'c44acc2327ef633b4d754dcb69dd07e5'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get data features
        $features = Feature::get();

        foreach ($this->data as $datum) {
            // Create project
            $project = new Project();
            $project->fill($datum);
            $project->save();
            // Collect menus
            $menus = [];
            foreach ($features as $feat) {
                $menus[$feat->id] = ['flag_active' => true];
            }
            // Set project menus
            $project->menus()->sync($menus);
        }
    }
}
