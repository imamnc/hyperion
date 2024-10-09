<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Itpi\Http\Controllers\Controller;
use Itpi\Models\Project;
use Itpi\Models\Settings;
use Itpi\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get data
            $data = User::where('type', 'user')
                ->orderBy('created_at', 'asc');
            // Filter by service
            if ($request->project_id) {
                $data->where('project_id', $request->project_id);
            }
            // Return datatable
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('option', function ($dat) {
                    return view('panel.user.datatable.option', compact('dat'));
                })
                ->addColumn('project', function ($dat) {
                    return $dat->project->name;
                })
                ->rawColumns(['option', 'project'])
                ->make(true);
        }
        // Get Data Project
        $projects = Project::get();
        // Return view
        return view('panel.user.index', compact('projects'));
    }

    public function reset_pin($id)
    {
        // Find
        $admin = User::findOrFail(base64_decode($id));
        // Get settings
        $settings = Settings::first();

        // Delete
        try {
            DB::beginTransaction();
            // Delete
            $admin->update([
                'pin' => Hash::make($settings->pin_default)
            ]);
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
            'message' => 'PIN user telah direset'
        ]);
    }

    public function delete($id)
    {
        // Find
        $admin = User::findOrFail(base64_decode($id));

        // Delete
        try {
            DB::beginTransaction();
            // Delete
            $admin->delete();
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
            'message' => 'Data user telah dihapus'
        ]);
    }
}
