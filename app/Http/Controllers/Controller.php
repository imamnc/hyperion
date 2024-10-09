<?php

namespace Itpi\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static $unauthenticatedMessage = 'Session anda telah habis!';

    public function responseMessage($message = 'success', $code = 200)
    {
        // Check https status code
        $code = ($code == 0) ? 500 : $code;
        // Return response
        return response()->json([
            'message' => $message
        ], $code);
    }

    public function responseApi($data = [], $message = 'success', $code = 200)
    {
        // Check https status code
        $code = ($code == 0) ? 500 : $code;
        // Return response
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
