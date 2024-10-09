<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Itpi\Http\Controllers\Controller;
use Itpi\Models\Settings;
use Itpi\Models\User;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get data
            $data = User::where('type', 'admin')
                ->where('id', '!=', Auth::user()->id)
                ->orderBy('created_at', 'asc')
                ->get();
            // Return datatable
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('option', function ($dat) {
                    return view('panel.admin.datatable.option', compact('dat'));
                })
                ->rawColumns(['option'])
                ->make(true);
        }
        // Return view
        return view('panel.admin.index');
    }

    public function create(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        // Response data not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validation_error' => $validator->errors()
            ], 422);
        }

        // Get settings
        $settings = Settings::first();

        // Define request data
        $data = $request->except('_token');
        $data['type'] = 'admin';
        $data['password'] = Hash::make($settings->password_default);

        // Create
        try {
            DB::beginTransaction();
            // Create
            User::create($data);
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
            'message' => 'Admin baru telah dibuat'
        ]);
    }

    public function update(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|string',
            'email' => "required|email|unique:users,email,$request->id,id",
        ]);

        // Response data not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validation_error' => $validator->errors()
            ], 422);
        }

        // Find admin
        $admin = User::findOrFail($request->id);

        // Define request data
        $data = $request->except('_token', 'id');
        $data['type'] = 'admin';
        $data['password'] = Hash::make('12345678');

        // Create
        try {
            DB::beginTransaction();
            // Create
            $admin->update($data);
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
            'message' => 'Perubahan data admin telah disimpan'
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
            'message' => 'Data admin telah dihapus'
        ]);
    }
}
