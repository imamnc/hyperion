<?php

namespace Itpi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Itpi\Models\Project;

class FeaturePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $required_feature)
    {
        // Find project
        $project = Project::findOrFail(auth()->user()->project_id);
        // Get project menus
        $menus = $project->menus;
        // Check project feature status
        $menu = $menus->where('code', $required_feature)->first();
        // Set permission
        $permission = false;
        // Override permission
        if ($menu) {
            if ($menu->pivot->flag_active) {
                $permission = true;
            }
        }
        // Check permission
        if ($permission) {
            return $next($request);
        } else {
            return response()->json(['message' => "Fitur tidak tersedia pada service ini !"], 404);
        }
    }
}
