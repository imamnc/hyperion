<?php

namespace Itpi\Providers;

use Illuminate\Support\ServiceProvider;
use Itpi\Core\Contracts\ServiceContract;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ServiceContract::class, function () {
            // Prepare project query
            $project = \Itpi\Models\Project::query();
            // Get project
            if (auth()->check()) {
                $project = $project->where('id', '=', auth()->user()->project_id)->first();
            } else {
                $project = $project->where('code', '=', request()->project)->first();
            }
            // Open Service
            $class = "\Itpi\Core\Services\\$project->class";
            $ref = new ReflectionClass($class);
            $object = $ref->newInstanceArgs(array($project));
            return $object;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
