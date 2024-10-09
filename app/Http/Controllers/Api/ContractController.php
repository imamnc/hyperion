<?php

namespace Itpi\Http\Controllers\Api;

use Illuminate\Http\Request;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Http\Controllers\Controller;
use Itpi\Http\Requests\GetContractDetailRequest;
use Itpi\Http\Requests\GetContractDocsRequest;
use Itpi\Http\Requests\GetContractRequest;

class ContractController extends Controller
{
    public function index(GetContractRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->contractList($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function detail(GetContractDetailRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->contractDetail($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }

    public function documents(GetContractDocsRequest $request, ServiceContract $service)
    {
        try {
            // Get response
            $response = $service->contractDocument($request->toArray());
            // Check response
            return $this->responseApi($response);
        } catch (\Throwable $e) {
            // Return error response
            return $this->responseMessage($e->getMessage(), $e->getCode());
        }
    }
}
