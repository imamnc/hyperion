<?php

namespace Itpi\Http\Controllers\Api;

use Itpi\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Itpi\Models\Project;

class MenuController extends Controller
{
    public function projects()
    {
        $projects = Project::query()->get();

        return $this->responseApi($projects);
    }
}
