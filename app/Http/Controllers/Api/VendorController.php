<?php

namespace Itpi\Http\Controllers\Api;

use Itpi\Core\Contracts\ServiceContract;
use Itpi\Http\Controllers\Controller;
use Itpi\Http\Requests\GetBlacklistRequest;
use Itpi\Http\Requests\GetVendorRequest;

class VendorController extends Controller
{
    public function index(GetVendorRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->vendorList($request->toArray());
            // Return response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function blacklist(GetBlacklistRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->blacklist($request->toArray());
            // Return response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }
}
