<?php

namespace Itpi\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Http\Controllers\Controller;
use Itpi\Http\Requests\LoginRequest;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request, ServiceContract $service): \Illuminate\Http\JsonResponse
    {
        try {
            // Get response
            $response = $service->login($request->toArray());
            // Return success response
            return $this->responseApi(['token' => $response]);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function checkPin(Request $request)
    {
        // Check authentication
        if (Auth::check()) {
            // Check PIN
            if (Hash::check($request->pin, Auth::user()->pin)) {
                return $this->responseApi(true, 'PIN Benar');
            } else {
                return $this->responseApi(false, 'PIN salah!');
            }
        } else {
            // Return error response
            return $this->responseMessage(self::$unauthenticatedMessage, 401);
        }
    }
}
