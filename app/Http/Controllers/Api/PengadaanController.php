<?php

namespace Itpi\Http\Controllers\Api;

use Illuminate\Http\Request;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Http\Controllers\Controller;
use Itpi\Http\Requests\GetPengadaanDetailRequest;
use Itpi\Http\Requests\GetPengadaanRequest;

class PengadaanController extends Controller
{
    public function index(GetPengadaanRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->pengadaanList($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function detail(GetPengadaanDetailRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->pengadaanDetail($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }
}
