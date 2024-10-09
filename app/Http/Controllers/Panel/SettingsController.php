<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Itpi\Http\Controllers\Controller;
use Itpi\Models\Feature;
use Itpi\Models\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        // Get settings
        $settings = Settings::first();
        // Get features
        $features = Feature::get();

        return view('panel.settings.index', compact('settings', 'features'));
    }

    public function save(Request $request)
    {
        // Find Settings
        $settings = Settings::first();

        // Save general settings
        if ($request->action == 'general') {
            // Validate request
            $request->validate([
                'app_version' => 'required',
                'assets_version' => 'required'
            ]);
            // Get request data
            $general = $request->except('_token', 'action');
            try {
                DB::beginTransaction();
                // Settings update
                $settings->update($general);
                // Commit
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollback();
                if (env('APP_ENV') != 'production') {
                    dd($e->getMessage());
                }
                // Return error message
                return redirect()->back()->with('toast-error', 'Gagal meyimpan settings !');
            }
            // Success message
            return redirect()->back()->with('toast-success', 'Perubahan data settings telah disimpan');
        }

        // Save security settings
        if ($request->action == 'security') {
            // Get request data
            $security = $request->except('_token', 'action');
            // Validate request
            $validator = Validator::make($security, [
                'password_default' => 'required|string|min:8',
                'pin_default' => 'required|string|numeric|digits:6'
            ]);
            // Response data not valid
            if ($validator->fails()) {
                session()->flash('tab', 'security');
            }
            // Validate
            $validator->validate();

            try {
                DB::beginTransaction();
                // Settings update
                $settings->update($security);
                // Commit
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollback();
                if (env('APP_ENV') != 'production') {
                    dd($e->getMessage());
                }
                // Return error message
                return redirect()->back()->with('toast-error', 'Gagal meyimpan settings !');
            }
            // Success message
            return redirect()->back()->with('toast-success', 'Perubahan data settings telah disimpan');
        }
    }
}
