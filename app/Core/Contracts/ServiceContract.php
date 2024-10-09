<?php

namespace Itpi\Core\Contracts;

/**
 * ITPI E-Procurement Aggregator Service Contract
 */
interface ServiceContract
{
    public function login(array $request);

    public function loginVendor(array $request);

    public function userDetail();

    public function vendorList(array $request);

    public function blacklist(array $request);

    public function pengadaanList(array $request);

    public function pengadaanDetail(array $request);

    public function PRList(array $request);

    public function PRDetail(array $request);

    public function contractList(array $request);

    public function contractDetail(array $request);

    public function contractDocument(array $request);
}
