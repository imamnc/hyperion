<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Itpi\Http\Controllers\Controller;
use Itpi\Models\Feature;
use Itpi\Models\Project;
use Itpi\Models\User;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get Data Project
            $data = Project::with('menus')->orderBy('name')->get();
            // Return datatable
            return DataTables::of($data->makeVisible(['url', 'key']))
                ->addIndexColumn()
                ->addColumn('option', function ($dat) {
                    return view('panel.project.datatable.option', compact('dat'));
                })
                ->rawColumns(['option'])
                ->make(true);
        }
        // Features
        $features = Feature::orderBy('name', 'asc')->get();
        // Return view
        return view('panel.project.index', compact('features'));
    }

    public function create(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:projects,name',
            'code' => 'required|max:4|unique:projects,code',
            'class' => 'required|unique:projects,class',
            'url' => 'required|url|unique:projects,url',
            'key' => 'required',
        ]);

        // Response data not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validation_error' => $validator->errors()
            ], 422);
        }

        // Define request data
        $data = $request->except('_token', 'menus_add');
        $data['encryption'] = 'md5';

        // Get Features
        $features = Feature::get();
        // Get request menus
        $req_menus = $request->menus_add ? $request->menus_add : [];
        // Data menus
        $menus = [];
        foreach ($features as $feat) {
            if (in_array($feat->id, $req_menus)) {
                $menus[$feat->id] = ['flag_active' => true];
            } else {
                $menus[$feat->id] = ['flag_active' => false];
            }
        }

        // Create
        try {
            DB::beginTransaction();
            // Create
            $project = Project::create($data);
            // Attach menus
            $project->menus()->attach($menus);
            // Commit
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            // Error message
            return response()->json([
                'success' => false,
                'message' => str_replace("`", '', $e->getMessage())
            ], 500);
        }

        // Success message
        return response()->json([
            'success' => true,
            'message' => 'Project baru telah dibuat'
        ]);
    }

    public function update(Request $request)
    {
        // Find project
        $project = Project::findOrFail($request->id);

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:projects,name,$project->id,id",
            'code' => "required|max:4|unique:projects,code,$project->id,id",
            'class' => "required|unique:projects,class,$project->id,id",
            'url' => "required|url|unique:projects,url,$project->id,id",
            'key' => "required",
        ]);

        // Response data not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validation_error' => $validator->errors()
            ], 422);
        }

        // Define request data
        $data = $request->except('_token', 'menus_edit');

        // Get Features
        $features = Feature::get();
        // Get request menus
        $old_menus = $request->menus_edit ? $request->menus_edit : [];
        // Data menus
        $menus = [];
        foreach ($features as $feat) {
            if (in_array($feat->id, $old_menus)) {
                $menus[$feat->id] = ['flag_active' => true];
            } else {
                $menus[$feat->id] = ['flag_active' => false];
            }
        }

        // Create
        try {
            DB::beginTransaction();
            // Create
            $project->update($data);
            // Update menus
            $project->menus()->sync($menus);
            // Commit
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            // Error message
            return response()->json([
                'success' => false,
                'message' => str_replace("`", '', $e->getMessage())
            ], 500);
        }

        // Success message
        return response()->json([
            'success' => true,
            'message' => 'Perubahan data project telah disimpan'
        ]);
    }

    public function delete($id)
    {
        // Find
        $project = Project::findOrFail(base64_decode($id));

        try {
            DB::beginTransaction();
            // Delete
            $project->menus()->detach();
            $project->delete();
            // Commit
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            // Error message
            return response()->json([
                'success' => false,
                'message' => str_replace("`", '', $e->getMessage())
            ], 500);
        }

        // Success message
        return response()->json([
            'success' => true,
            'message' => 'Data project telah dihapus'
        ]);
    }
}
