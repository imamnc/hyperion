<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Itpi\Http\Controllers\Controller;
use Itpi\Models\Feature;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get feature
            $features = Feature::orderBy('id')->get();
            // Render view
            return view('panel.feature.list', compact('features'));
        } else {
            abort(404);
        }
    }

    public function create(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:features,name',
            'code' => 'required|unique:features,code',
        ]);

        // Response data not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validation_error' => $validator->errors()
            ], 422);
        }

        // Define request data
        $data = $request->except('_token');

        // Create
        try {
            DB::beginTransaction();
            // Create
            Feature::create($data);
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
            'message' => 'Fitur baru telah ditambahkan'
        ]);
    }

    public function update(Request $request)
    {
        // Find feature
        $feature = Feature::findOrFail($request->id);

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:features,name,$feature->id,id",
            'code' => "required|unique:features,code,$feature->id,id"
        ]);

        // Response data not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validation_error' => $validator->errors()
            ], 422);
        }

        // Get request data
        $data = $request->except('_token', 'id');

        // Create
        try {
            DB::beginTransaction();
            // Create
            $feature->update($data);
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
            'message' => 'Perubahan data fitur telah disimpan'
        ]);
    }

    public function delete($id)
    {
        // Find
        $feature = Feature::findOrFail(base64_decode($id));

        try {
            DB::beginTransaction();
            // Detach projects
            $feature->projects()->detach();
            // Delete
            $feature->delete();
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
            'message' => 'Data fitur telah dihapus'
        ]);
    }
}
