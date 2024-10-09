<?php

namespace Itpi\Core\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Models\Project;

class DefaultService extends BaseService implements ServiceContract
{
    protected Project $project;

    public function __construct(Project $project)
    {
        parent::__construct();
        $this->project = $project;
    }

    /**
     * @param Project $project
     * @param array $request
     * @return \Exception|GuzzleException|mixed
     */
    public function login(array $request)
    {
        try {
            $response = $this->client->post($this->project->url . '/auth/login/admin', [
                'json' => $request
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return $e;
        }
    }

    public function userDetail()
    {
        // TODO: Implement userDetail() method.
    }

    public function vendorList(array $request)
    {
        // TODO: Implement vendorList() method.
    }

    public function blacklist(array $request)
    {
        // TODO: Implement blacklist() method.
    }

    public function pengadaanList(array $request)
    {
        // TODO: Implement pengadaanList() method.
    }

    public function pengadaanDetail(array $request)
    {
        // TODO: Implement pengadaanList() method.
    }

    public function PRList(array $request)
    {
        // TODO
    }

    public function PRDetail(array $request)
    {
    }

    public function contractList(array $request)
    {
        return null;
    }

    public function contractDetail(array $request)
    {
        throw new \Exception("Service tidak mempunyai fitur manajemen kontrak !");
    }

    public function contractDocument(array $request)
    {
        throw new \Exception("Service tidak mempunyai fitur manajemen kontrak !");
    }
}
