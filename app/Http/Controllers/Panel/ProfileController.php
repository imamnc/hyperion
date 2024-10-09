<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Itpi\Http\Controllers\Controller;
use Itpi\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('panel.profile.index');
    }

    public function update_image(Request $request)
    {
        // Set destination forlder
        $folderPath = public_path('img/profile/');

        // Explode to part
        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        // Encode to base64
        $image_base64 = base64_decode($image_parts[1]);

        // Filename
        $filename = strtoupper(uniqid('PROFILE-')) . '.' . $image_type;
        $file = $folderPath .  $filename;

        // Put content to server
        file_put_contents($file, $image_base64);

        // Delete file lama
        if (Auth::user()->profile_photo_path != null) {
            if (file_exists(public_path(Auth::user()->profile_photo_path))) {
                unlink(public_path(Auth::user()->profile_photo_path));
            }
        }

        $user = User::find(Auth::id());
        $update = $user->update([
            'profile_photo_path'    => "img/profile/$filename"
        ]);

        // Genereate response
        if ($update) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
