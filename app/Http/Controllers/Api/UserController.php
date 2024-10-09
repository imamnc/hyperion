<?php

namespace Itpi\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(Request $request, ServiceContract $service): \Illuminate\Http\JsonResponse
    {
        try {
            // Get response
            $response = $service->userDetail();
            // Return success response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function menu()
    {
        // Get User Menus
        $user = Auth::user();
        // Loop
        foreach ($user->project->menus as $key => $men) {
            $user->project->menus[$key]->name = $men->name;
            $user->project->menus[$key]->code = $men->code;
            $user->project->menus[$key]->active = $men->pivot->flag_active;
            unset($user->project->menus[$key]->id);
            unset($user->project->menus[$key]->created_at);
            unset($user->project->menus[$key]->updated_at);
            unset($user->project->menus[$key]->pivot);
        }
        // Return response
        return $this->responseApi($user->project->menus);
    }

    public function resetPin(Request $request)
    {
        // Check authentication
        if (Auth::check()) {
            // Check PIN
            if (Hash::check($request->old_pin, Auth::user()->pin)) {
                if ($request->pin == $request->pin_confirmation) {
                    $user = Auth::user();
                    $user->fill([
                        'pin' => Hash::make($request->pin)
                    ]);
                    $user->save();
                    return $this->responseApi(true, 'PIN telah diubah!');
                } else {
                    return $this->responseApi(false, 'PIN tidak sama!');
                }
            } else {
                return $this->responseApi(false, 'PIN Lama salah!');
            }
        } else {
            // Return error response
            return $this->responseMessage(self::$unauthenticatedMessage, 401);
        }
    }
}
