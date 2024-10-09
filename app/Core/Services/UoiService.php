<?php

namespace Itpi\Core\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Models\Project;
use Itpi\Models\User;

class UoiService extends BaseService implements ServiceContract
{
    protected Project $project;

    public function __construct(Project $project)
    {
        parent::__construct();
        // Set project data
        $this->project = $project;
    }

    public function login(array $request)
    {
        // Get project
        $project = Project::query()->where('code', '=', $request['project'])->first();

        // Create request data
        $request = [
            'grant_type' => 'password',
            'username' => $request['email'],
            'password' => $request['password'],
        ];

        try {
            // Request login
            $login_data = Http::withoutVerifying()->asForm()->post($this->project->url . '/login', $request)->json();
            // Throw error 422 if response login failed
            if (isset($login_data['error'])) {
                throw new \Exception("Email atau Password salah !", 422);
            }
            // Get user
            $user = User::query()->where('email', '=', $request['username'])->where('project_id', '=', $project->id)->first();
            // Check user
            if ($user) {
                // Save session ID
                $user->fill(['service_token' => $login_data['access_token']]);
                $user->save();
            } else {
                // Create new user and save session ID
                $user = new User();
                $user->fill([
                    'project_id' => $project->id,
                    'name' => $request['username'],
                    'email' => $request['username'],
                    'service_token' => $login_data['access_token'],
                    'pin' => Hash::make('123456'),
                    'type' => 'user'
                ]);
                $user->save();
            }
            // Return token
            return $user->createToken($user->name)->plainTextToken;
        } catch (\Throwable $e) {
            $this->serviceError($e);
        }
    }

    public function userDetail()
    {
        try {
            // Request user data
            $raw = Http::withoutVerifying()->asForm()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/admin/dashboardAdmin/getUsername')->json();

            // Check user session status & permission
            $this->netCheckSession($raw);

            // Format response
            $response = auth()->user();
            $response->project_name = $response->project->name;
            $response->eproc_data = ['username' => $raw];

            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function vendorList(array $request)
    {
        // Create request
        $request = [
            'Offset' => ($request['page'] - 1) * $request['limit'],
            'Limit' => $request['limit'],
            'Keyword' => isset($request['keyword']) ? $request['keyword'] : '',
            'Status' => 0
        ];

        // Validate value offset harus lebih dari 0
        if ($request['Offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Request vendor data
            $raw = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/public/verifiedvendor/select', $request)->json();

            // Check user session status & permission
            $this->netCheckSession($raw);

            // Get list of vendor data
            $data = $raw['List'];

            // Format response
            $response = [];
            foreach ($data as $key => $dat) {
                // Generate status
                $status = $this->netVendorStatus($dat);
                // Response item
                $response[] = [
                    'nama_perusahaan' => $dat['VendorName'],
                    'created_date' => $dat['CreatedDate'],
                    'status' => $status,
                ];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->ServiceError($e);
        }
    }

    public function blacklist(array $request)
    {
        // Convert flag_status from string to boolean
        $flag_status = filter_var($request['flag_status'], FILTER_VALIDATE_BOOLEAN);
        // Create offset
        $offset = ($request['page'] - 1) * $request['limit'];
        // Validate value offset harus lebih dari 0
        if ($offset < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        // Create data request
        $request = [
            'Offset' => $offset,
            'Limit' => $request['limit'],
            'Keyword' => isset($request['search']) ? $request['search'] : '',
            'Boolean' => $flag_status,
            'Status' => 0
        ];

        try {
            // Request blacklist data
            $raw = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/public/blacklist/selectblacklist', $request)->json();

            // Check user session status & permission
            $this->netCheckSession($raw);

            // Get list data
            $data = $raw['List'];

            $response = [];
            foreach ($data as $key => $dat) {
                $response[] = [
                    'nama_perusahaan' => $dat['Vendor']['VendorName'],
                    'blacklist_date' => $dat['StartDate'] ? date('Y-m-d H:i:s', strtotime($dat['StartDate'])) : null,
                    'kategori_blacklist' => $this->netTranslate('uoi', $dat['ModuleDetail']['Value']) . ' ' . $this->netTranslate('uoi', $dat['MasaBlacklist']['Value']),
                    'start_date' => date('Y-m-d H:i:s', strtotime($dat['StartDate'])),
                    'end_date' => date('Y-m-d H:i:s', strtotime($dat['EndDate'])),
                    'status' => $this->netTranslate('uoi', $dat['ApprovalStatus']['Value'])
                ];
            }

            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function pengadaanList(array $request)
    {
        // Get tipe pengadaan
        $tipe_pengadaan = $request['tipe_pengadaan'];
        // Validate tipe pengadaan
        if (!in_array($tipe_pengadaan, ['etender', 'rfq', 'ekspedisi'])) {
            throw new \Exception("Tipe pengadaan tidak valid !", 422);
        }

        // Create request data
        $request = [
            'Column' => 1,
            'Keyword' => isset($request['keyword']) ? $request['keyword'] : '',
            'Offset' => ($request['page'] - 1) * $request['limit'],
            'Limit' => $request['limit']
        ];
        // Validate value offset harus lebih dari 0
        if ($request['Offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Try to request
            if ($tipe_pengadaan == 'etender') { // If Ekspedisi
                $raw = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/admin/tender/select', $request)->json();

                // Check user session status & permission
                $this->netCheckSession($raw);

                // Get data
                $data = $raw['List'];

                // Generate response
                $response = [];
                foreach ($data as $key => $dat) {
                    // Get last tahapan
                    $tahapan = collect($dat['TenderStepDatas']);
                    // Generate response
                    $response[] = [
                        'paket_id' => $dat['ID'],
                        'nomor_pr' => $dat['TenderCode'],
                        'nama_pengadaan' => $dat['TenderName'],
                        'tipe' => $tipe_pengadaan,
                        'tahapan' => ($tahapan->last()) ? $tahapan->last()['step']['TenderStepName'] : null
                    ];
                }
            } else {
                $response = [];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function pengadaanDetail(array $request)
    {
        // Get tipe pengadaan
        $tipe_pengadaan = $request['tipe_pengadaan'];
        // Get paket_lelang_id
        $paket_lelang_id = $request['paket_lelang_id'];

        try {
            if ($tipe_pengadaan == 'etender') {
                // Request to pengadaan detail
                $raw_detail = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/admin/tender/select', [
                    'Column' => 2,
                    'Keyword' => $paket_lelang_id,
                    'Offset' => 0,
                    'Limit' => 1
                ])->json();

                // Check user session status & permission
                $this->netCheckSession($raw_detail);

                // Get detal data
                $detail = isset($raw_detail['List'][0]) ? $raw_detail['List'][0] : null;

                // Return null if data not found
                if ($detail == null) {
                    throw new \Exception("Paket lelang ID tidak valid !", 422);
                }

                // Request PR Items
                $raw_rfqvhs = Http::withoutVerifying()->withToken(auth()->user()->service_token)
                    ->post($this->project->url . '/apps/astra/api/admin/RFQVHS/loadRFQVHS', ['ID' => $detail['RFQVHSDatas']['ID']])->json();
                $pr_items = $raw_rfqvhs['RFQVHSItems'];
                // Format pr
                $pekerjaans = [];
                foreach ($pr_items as $key => $item) {
                    $pekerjaans[] = [
                        'nama_item' => $item['Material'],
                        'nomor_pr' => $item['PurchaseReq'],
                        'deskripsi_item' => $item['MaterialDescription'],
                        'satuan' => $item['UnitOfMeasure'],
                        'currency' => $item['Currency'],
                        'jumlah' => $item['Quantity'],
                        'harga_satuan' => $item['UnitCost'],
                        'harga_total' => $item['AwardedVendorTotalPrice'],
                    ];
                }

                // Request komoditi
                $raw_komoditis = Http::withoutVerifying()->withToken(auth()->user()->service_token)
                    ->post($this->project->url . '/apps/astra/api/public/PurchaseRequisition/getCommodities')->json();
                $komoditis = collect($raw_komoditis);
                $komoditi = $komoditis->where('ID', $detail['RFQVHSDatas']['CommodityID'])->first();

                // Reqeuest metode tender
                $raw_metode_tenders = Http::withoutVerifying()->withToken(auth()->user()->service_token)
                    ->post($this->project->url . '/apps/astra/api/public/procMethod/select', [
                        'Keyword' => '',
                        'Offset' => 0,
                        'Limit' => 1000
                    ])->json();
                $metode_tenders = collect($raw_metode_tenders['List']);
                $metode_tender = $metode_tenders->where('MethodID', $detail['RFQVHSDatas']['ProcMethod'])->first();

                // Get tahapan
                $tahapans = collect($detail['TenderStepDatas']);
                // Format tahapan
                $response_tahapan = [];
                foreach ($tahapans as $key => $tahap) {
                    $response_tahapan[] = [
                        'nama_tahapan' => $tahap['step']['TenderStepName'],
                        'start_date' => date('Y-m-d H:i:s', strtotime($tahap['StartDate'])),
                        'end_date' => date('Y-m-d H:i:s', strtotime($tahap['EndDate'])),
                    ];
                }

                // Create response
                $response = [
                    'nama_pengadaan' => $detail['TenderName'],
                    'pr_type' => $detail['RFQVHSDatas']['PRTypeDetail']['Description'],
                    'komoditi_type' => $komoditi ? $komoditi['Name'] : null,
                    'currency' => 'IDR',
                    'metode_pengadaan' => $metode_tender ? $metode_tender['MethodName'] : null,
                    'metode_evaluasi' => $detail['RFQVHSDatas']['EvaluationMethod']['EvaluationMethodName'],
                    'last_tahapan' => $tahapans->last() ? $tahapans->last()['step']['TenderStepName'] : null,
                    'tahapan' => $response_tahapan,
                    'pekerjaan' => $pekerjaans
                ];
            }

            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function PRList(array $request)
    {
        $request = [
            "keyword" => '',
            "keyword2" => $request['keyword'],
            "keyword3" => '',
            "keyword4" => '',
            "Offset" => ($request['page'] - 1) * $request['limit'],
            "Limit" => $request['limit']
        ];

        // Validate value offset harus lebih dari 0
        if ($request['Offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Reqeust data pr
            $raw = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/public/PurchaseRequisition/selectitemprbypgr', $request)->json();

            // Check user session status & permission
            $this->netCheckSession($raw);

            // Get list response data
            $data = $raw['List'];

            // Dictionary status
            $status = [
                "ITEMPR_STATUS_OUTSTANDING" => "Outstanding",
                "ITEMPR_STATUS_SUBMITTED" => "Submitted",
                "ITEMPR_STATUS_ONGOING" => "On Going",
                "ITEMPR_STATUS_CANCELLED" => "Cancelled",
                "ITEMPR_STATUS_AWARDED" => "Awarded",
                "ITEMPR_STATUS_NONTENDER" => "Non Tender",
            ];

            // Formatting response
            $response = [];
            foreach ($data as $key => $dat) {
                $response[] = [
                    'project_type' => 'dotnet',
                    'nomor_pr' => $dat['PurchaseReq'],
                    'pr_id' => $dat['ID'],
                    'nama_pr' => $dat['ShortText'],
                    'tipe_pr' => null,
                    'komiditi' => $dat['CommodityName'],
                    'currency' => $dat['Currency'],
                    'quantity' => $dat['Quantity'],
                    'satuan' => $dat['UnitMeasure'],
                    'status' => isset($status[$dat['StatusString']]) ? $status[$dat['StatusString']] : null,
                ];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function PRDetail(array $request)
    {
        // Create request data
        $request = [
            "keyword" => $request['nomor_pr'],
            "keyword2" => '',
            "keyword3" => '',
            "keyword4" => '',
            "Offset" => 0,
            "Limit" => 1
        ];

        try {
            // Try to request
            $raw = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/apps/astra/api/public/PurchaseRequisition/selectitemprbypgr', $request)->json();

            // Check user session status & permission
            $this->netCheckSession($raw);

            // Get response data
            $data = collect($raw['List'])->first();

            // Validate that result is exists
            if ($data == null) {
                return null;
            }

            // Dictionary status
            $status = [
                "ITEMPR_STATUS_OUTSTANDING" => "Outstanding",
                "ITEMPR_STATUS_SUBMITTED" => "Submitted",
                "ITEMPR_STATUS_ONGOING" => "On Going",
                "ITEMPR_STATUS_CANCELLED" => "Cancelled",
                "ITEMPR_STATUS_AWARDED" => "Awarded",
                "ITEMPR_STATUS_NONTENDER" => "Non Tender",
            ];

            // Generate response
            $response = [
                'project_type' => 'dotnet',
                'nomor_pr' => $data['PurchaseReq'],
                'pr_id' => $data['ID'],
                'nama_pr' => $data['ShortText'],
                'tipe_pr' => null,
                'komiditi' => $data['CommodityName'],
                'currency' => $data['Currency'],
                'quantity' => $data['Quantity'],
                'satuan' => $data['UnitMeasure'],
                'status' => isset($status[$data['StatusString']]) ? $status[$data['StatusString']] : null,
            ];

            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function contractList(array $request)
    {
        // Service not available
    }

    public function contractDetail(array $request)
    {
        // Service not available
    }

    public function contractDocument(array $request)
    {
        // Service not available
    }
}
