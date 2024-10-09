<?php

namespace Itpi\Http\Controllers\Api;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Http\Controllers\Controller;
use Itpi\Http\Requests\GetPRDetailRequest;
use Itpi\Http\Requests\GetPRRequest;

class PRController extends Controller
{
    public function index(GetPRRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->PRList($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function detail(GetPRDetailRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->PRDetail($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }
}
