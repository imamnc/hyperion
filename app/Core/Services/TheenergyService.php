<?php

namespace Itpi\Core\Services;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Itpi\Core\Contracts\ServiceContract;
use Itpi\Models\Project;
use Itpi\Models\User;

class TheenergyService extends BaseService implements ServiceContract
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
        // Get Project Data
        $project = Project::where('code', '=', $request['project'])->first();

        // Create form data request
        $form_data = [
            'username' => $request['email'],
            'password' => $request['password'],
            'api_key' => $project->key
        ];

        try {
            // Request login to API THE ENERGY
            $raw = Http::withoutVerifying()->post($this->project->url . '/auth/login/admin', $form_data)->json();
            $login_data = $raw['data'];
            // Throw error 422 if response login failed
            if ($login_data['success'] == false) {
                throw new \Exception("Email atau Password salah !", 422);
            }
            // Get user
            $user = User::where('email', '=', $request['email'])->where('project_id', '=', $project->id)->first();
            // Check user
            if ($user) {
                // Save session ID
                $user->fill(['service_token' => $login_data['session_id']]);
                $user->save();
            } else {
                // Create new user and save session ID
                $user = new User();
                $user->fill([
                    'project_id' => $project->id,
                    'name' => $request['email'],
                    'email' => $request['email'],
                    'service_token' => $login_data['session_id'],
                    'pin' => Hash::make('123456'),
                    'type' => 'user'
                ]);
                $user->save();
            }
            // Create token
            return $user->createToken($user->name)->plainTextToken;
        } catch (\Throwable $e) {
            // Throw Service Error
            $this->serviceError($e);
        }
    }

    public function userDetail()
    {
        try {
            // Try to get user data
            $raw = Http::withoutVerifying()->withToken(auth()->user()->service_token)->post($this->project->url . '/auth/get_session', ['session_id' => auth()->user()->service_token])->json();

            // Check session status
            $this->phpCheckSession($raw);

            // Format response data
            $sessionData = $raw['data']['session_data'];
            unset($sessionData['password']);
            $response = auth()->user();
            $response->project_name = $response->project->name;
            $response->eproc_data = $sessionData;

            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function vendorList(array $request)
    {
        // Formating search keyword
        $keyword = isset($request['keyword']) ? '%' . $request['keyword'] . '%' : '%%';

        // Create request
        $request['status'] = 0;
        $request['otv'] = null;
        $request['company'] = $keyword;
        $request['offset'] = ($request['page'] - 1) * $request['limit'];

        // Validate value offset harus lebih dari 0
        if ($request['offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Request data vendor
            $raw = Http::withoutVerifying()->post($this->project->url . '/rekanan/searchJoinBu', $request)->json();
            $data = $raw['result']['data']['data'];
            // Format response
            $response = [];
            foreach ($data as $key => $dat) {
                // Genrate status
                $status = $this->phpVendorStatus($dat);
                // Response item
                $response[] = [
                    'nama_perusahaan' => $dat['nama_perusahaan'],
                    'created_date' => $dat['created_date'],
                    'status' => $status,
                ];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return response error
            $this->serviceError($e);
        }
    }

    public function blacklist(array $request)
    {
        // Reformat search
        $request['search'] = isset($request['search']) ? "%" . $request['search'] . "%" : "%%";
        // Convert flag_status from string to boolean
        $flag_status = filter_var($request['flag_status'], FILTER_VALIDATE_BOOLEAN);
        // Create offset
        $offset = ($request['page'] - 1) * $request['limit'];
        // Validate value offset harus lebih dari 0
        if ($offset < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }
        // Create limit
        $limit = $request['limit'];
        // Format request
        $request = [
            'type' => 0,
            'search' => $request['search'],
            'param' => [$flag_status],
            'actionView' => 'findBlacklist'
        ];

        try {
            // Try to get status aproval data
            $raw_status_approval = Http::withoutVerifying()->get($this->project->url . '/blacklist/status-approval')->json();
            $status_approval = $raw_status_approval['result']['data'];
            $status_approval = collect($status_approval);

            // Try to get blacklist data
            $raw_blacklist = Http::withoutVerifying()->post($this->project->url . '/blacklist/select', $request)->json();
            $data = $raw_blacklist['result']['data'][0];

            $data = collect($data);
            if ($limit > 0) {
                // Apply pagination using collection slice
                $data = $data->slice($offset, $limit)->toArray();
            }

            // Generate response
            $response = [];
            foreach ($data as $key => $dat) {
                // Set kategori blacklist
                if ($flag_status == true) {
                    if ($dat['jenis_blacklist'] == 1) {
                        $kategori_blacklist = "Blacklist Sementara";
                    } else if ($dat['jenis_blacklist'] == 2) {
                        $kategori_blacklist = "Blacklist Selamanya";
                    } else {
                        if ($dat['purchasing_block'] == null && $dat['payment_block'] == null) {
                            $kategori_blacklist = "Whitelist";
                        } else if ($dat['purchasing_block'] == null && $dat['payment_block'] != null) {
                            $kategori_blacklist = "Whitelist Payment";
                        } else if ($dat['kategori_blacklist_label'] != null && $dat['payment_block'] == null) {
                            $kategori_blacklist = "Whitelist Purchasing";
                        }
                    }
                } else {
                    $kategori_blacklist = null;
                }
                // Reformat start_date and end_date
                if (isset($dat['start_date'])) {
                    $start_date = ($dat['start_date'] != null) ? date('Y-m-d H:i:s', strtotime($dat['start_date'])) : null;
                } else {
                    $start_date = null;
                }
                if (isset($dat['end_date'])) {
                    $end_date = ($dat['end_date'] != null) ? date('Y-m-d H:i:s', strtotime($dat['end_date'])) : null;
                } else {
                    $end_date = null;
                }
                // Set status
                if (isset($dat['approval_status'])) {
                    $status = $status_approval->where('status_approval_id', $dat['approval_status'])->first();
                    $status = ($status != null) ? $status['status'] : null;
                } else {
                    $status = null;
                }
                // Create response
                $response[] = [
                    'nama_perusahaan' => $dat['nama_perusahaan'],
                    'blacklist_date' => $start_date,
                    'kategori_blacklist' => $kategori_blacklist,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => $status
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
        // Convert tipe_pengadaan to metode_pemilihan_penyedia_id
        $tipe_pengadaan = [
            'etender' => 3,
            'rfq' => 2,
            'ekspedisi' => 5
        ];
        // Validate tipe pengadaan
        if (!isset($tipe_pengadaan[$request['tipe_pengadaan']])) {
            throw new \Exception("Tipe pengadaan tidak valid !", 422);
        }
        // Set value metode_pemilihan_penyedia_id
        $metode_pemilihan_penyedia_id = $tipe_pengadaan[$request['tipe_pengadaan']];
        // Set keyword
        $request['keyword'] = isset($request['keyword']) ? $request['keyword'] : null;
        // Add request data
        $request['filter'] = ["status" => 0, "search" => "", "selectedStatus" => 5];
        $request['metode_pemilihan_penyedia_id'] = $metode_pemilihan_penyedia_id;
        $request['username'] = Auth::user()->email;
        $request['namaLelang'] = "%" . $request['keyword'] . "%";
        $request['namaLelangTercentang'] = true;
        $request['namaTahapan'] = "%" . $request['keyword'] . "%";
        $request['namaTahapanTercentang'] = false; // Disable search by namaTahapan
        $request['statusTercentang'] = false;
        $request['status'] = 5;
        $request['page_id'] = 5;
        $request['offset'] = ($request['page'] - 1) * $request['limit'];

        // Validate value offset harus lebih dari 0
        if ($request['offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Try to request
            if ($request['metode_pemilihan_penyedia_id'] == 5) { // If Ekspedisi
                $raw = Http::withoutVerifying()->post($this->project->url . '/paket/selectPaketEkspedisi', $request)->json();
                $data = $raw['result']['data']['getData'];
            } else {
                $raw = Http::withoutVerifying()->post($this->project->url . '/paket/admin/select', $request)->json();
                $data = $raw['result']['data']['data'];
            }
            // Create response
            $response = [];
            foreach ($data as $key => $dat) {
                // Request pengadaan by id
                $raw_detail = Http::withoutVerifying()->post($this->project->url . '/paket/byid', [
                    'param' => [$dat['paket_id']]
                ])->json();
                $detail = isset($raw_detail['result']['data'][0][0]) ? $raw_detail['result']['data'][0][0] : null;
                // Request tahapan
                $raw_tahapan = Http::withoutVerifying()->post($this->project->url . '/paket/detail/select', [
                    'metode_pemilihan_penyedia_id' => $metode_pemilihan_penyedia_id,
                    'param' => [$dat['paket_id']]
                ])->json();
                $tahapan = collect($raw_tahapan['result']['data']);
                // Request detail PR
                $raw_pr = Http::withoutVerifying()->post($this->project->url . '/PR/detailPRLelang', [
                    'pr_id' => $detail['pr_id']
                ])->json();
                $pr = $raw_pr['result']['data'][0];
                // Generate response
                $response[] = [
                    'paket_id' => $dat['paket_id'],
                    'nomor_pr' => $pr['pr_kode'],
                    'nama_pengadaan' => $dat['nama_paket'],
                    'tipe' => $request['tipe_pengadaan'],
                    'tahapan' => ($tahapan->last()) ? $tahapan->last()['nama_tahapan'] : null
                ];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Throw Service Error
            $this->serviceError($e);
        }
    }

    public function PengadaanDetail(array $request)
    {
        // Convert tipe_pengadaan to metode_pemilihan_oenyedia_id
        $tipe_pengadaan = [
            'etender' => 3,
            'rfq' => 2,
            'ekspedisi' => 5
        ];
        // Validate tipe pengadaan
        if (!isset($tipe_pengadaan[$request['tipe_pengadaan']])) {
            throw new \Exception("Tipe pengadaan tidak valid !", 422);
        }
        // Set value metode_pemilihan_penyedia_id
        $metode_pemilihan_penyedia_id = $tipe_pengadaan[$request['tipe_pengadaan']];

        // Paket lelang id
        $paket_id = $request['paket_lelang_id'];

        // Get request
        $request = [
            'metode_pemilihan_penyedia_id' => $metode_pemilihan_penyedia_id,
            'param' => [$paket_id]
        ];

        try {
            // Request pengadaan by id
            $raw_info = Http::withoutVerifying()->post($this->project->url . '/paket/byid', [
                'param' => [$request['param'][0]]
            ])->json();
            $detail = isset($raw_info['result']['data'][0][0]) ? $raw_info['result']['data'][0][0] : null;
            // Validate tipe pengadaan
            if ($detail == null) {
                throw new \Exception("Paket lelang ID tidak valid !", 422);
            }

            // Request detail PR
            $raw_pr = Http::withoutVerifying()->post($this->project->url . '/PR/detailPRLelang', [
                'pr_id' => $detail['pr_id']
            ])->json();
            $pr = isset($raw_pr['result']['data'][0]) ? $raw_pr['result']['data'][0] : null;

            // Request PR Type
            $raw_prtype = Http::withoutVerifying()->get($this->project->url . '/PR/getPRType')->json();
            $pr_types = $raw_prtype['result']['data'];
            $pr_types = collect($pr_types);
            $pr_type = ($pr) ? $pr_types->where('pr_doc_type_id', $pr['type_pr'])->first() : null;

            // Request komiditi type
            $raw_komoditi_type = Http::withoutVerifying()->post($this->project->url . '/PR/get_Jenis_komoditi')->json();
            $komoditi_types = $raw_komoditi_type['result']['data'];
            $komoditi_types = collect($komoditi_types);
            $komoditi_type = ($pr) ? $komoditi_types->where('jenis_pengadaan_id', $pr['jenis_komoditi'])->first() : null;

            // Request tahapan
            $raw_detail = Http::withoutVerifying()->post($this->project->url . '/paket/detail/select', $request)->json();
            $tahapan = $raw_detail['result']['data'];
            $tahapan = collect($tahapan);
            // Format tahapan
            $response_tahapan = $this->phpFormatTahapan($tahapan);

            // Request PR/getEachPr
            $raw_eachpr = Http::withoutVerifying()->post($this->project->url . '/PR/getEachPr', ['pr_id' => $detail['pr_id']])->json();
            $pekerjaans['dataDPR'] = $raw_eachpr['result']['data']['dataDPR'];
            $pekerjaans['dataDKM'] = $raw_eachpr['result']['data']['dataDKM'];
            $pekerjaans['dataDPS'] = $raw_eachpr['result']['data']['dataDPS'];

            // Add total cost
            foreach ($pekerjaans['dataDPR'] as $key => $dpr) {
                $pekerjaans['dataDPR'][$key]['total_cost'] = $dpr['prl_unit_cost'] * $dpr['prl_quantity'];
                $pekerjaans['dataDPR'][$key]['nomor_pr'] = $pr ? $pr['pr_kode'] : null;
            }

            // Create response
            $response = [
                'paket_id' => (int) $paket_id,
                'nama_pengadaan' => $detail['nama_paket'],
                'nomor_pr' => $pr ? $pr['pr_kode'] : null,
                'pr_type' => $pr_type ? $pr_type['description'] : null,
                'komoditi_type' => $komoditi_type ? $komoditi_type['jenis_pengadaan_nama'] : null,
                'currency' => $pr ? $pr['currency'] : null,
                'metode_pengadaan' => $detail['nama_metode_pengadaan'],
                'metode_evaluasi' => $detail['nama_metode_evaluasi'],
                'last_tahapan' => $tahapan->last() ? $tahapan->last()['nama_tahapan'] : null,
                'tahapan' => $response_tahapan,
                'pekerjaan' => $pekerjaans
            ];

            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return Service Error
            $this->serviceError($e);
        }
    }

    public function PRList(array $request)
    {
        $request = [
            "selectBy" => isset($request['keyword']) ? 'namaPr' : 'all',
            "selectByValue" => isset($request['keyword']) ? $request['keyword'] : 'all',
            "fdate" => [
                "value1" => null,
                "vluei2" => null
            ],
            "status" => 1,
            "cari" => null,
            "offset" => ($request['page'] - 1) * $request['limit'],
            "pagesize" => $request['limit'],
            "mengatur" => true,
            "menambah" => true,
            "cekapp" => false,
            "peg_id" => 4
        ];

        // Validate value offset harus lebih dari 0
        if ($request['offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Try to request
            $raw = Http::withoutVerifying()->post($this->project->url . '/PR/view', $request)->json();
            $data = $raw['result']['data']['data'];
            // Master status
            $master_status = [
                '0' => 'Draft',
                '1' => 'Proses Approval',
                '2' => 'Release',
                '3' => 'Direvisi',
                '4' => 'Dibatalkan',
                '14' => 'Unrelease',
                '12' => 'Partial Release',
                '13' => 'Release',
                '15' => 'Delete',
                '16' => 'Undelete',
                '17' => 'Revisi',
            ];
            // Format response
            $response = [];
            foreach ($data as $key => $dat) {
                $response[] = [
                    'project_type' => 'php',
                    'id' => $dat['id'],
                    'pr_id' => $dat['pr_id'],
                    'nama_pr' => $dat['pr_nama'],
                    'nomor_pr' => $dat['pr_kode'],
                    'tipe_pr' => $dat['nama_type_pr'],
                    'nama_barang' => $dat['material_description'],
                    'kode_barang' => $dat['material_number'],
                    'status' => isset($master_status[strval($dat['status_prl'])]) ? $master_status[strval($dat['status_prl'])] : null
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
        try {
            // Request detail PR
            $request_detail_pr = [
                "selectBy" => 'noPr',
                "selectByValue" => $request['nomor_pr'],
                "fdate" => [
                    "value1" => null,
                    "vluei2" => null
                ],
                "status" => 1,
                "cari" => null,
                "offset" => 0,
                "pagesize" => 1,
                "mengatur" => true,
                "menambah" => true,
                "cekapp" => false,
                "peg_id" => 4
            ];
            // Master status
            $master_status = [
                '0' => 'Draft',
                '1' => 'Proses Approval',
                '2' => 'Release',
                '3' => 'Direvisi',
                '4' => 'Dibatalkan',
                '14' => 'Unrelease',
                '12' => 'Partial Release',
                '13' => 'Release',
                '15' => 'Delete',
                '16' => 'Undelete',
                '17' => 'Revisi',
            ];
            $raw_pr = Http::withoutVerifying()->post($this->project->url . '/PR/view', $request_detail_pr)->json();
            $detail = $raw_pr['result']['data']['data'];
            $detail = collect($detail)->first();

            // Return null if data doesn't exists
            if ($detail == null) {
                throw new \Exception("Data PR tidak ditemukan !", 404);
            }

            // Get each pr
            $raw_eachpr = Http::withoutVerifying()->post($this->project->url . '/PR/getEachPr', ['pr_id' => $detail['pr_id']])->json();
            $data_eachpr = $raw_eachpr['result']['data'];

            // Format response
            $response[] = [
                'project_type' => 'php',
                'nomor_pr' => $detail['pr_kode'],
                'pr_id' => $detail['pr_id'],
                'nama_pr' => $detail['pr_nama'],
                'tipe_pr' => $detail['nama_type_pr'],
                'komiditi' => $detail['jenis_pengadaan_nama'],
                'currency' => $detail['currency'],
                'nama_barang' => $detail['material_description'],
                'kode_barang' => $detail['material_number'],
                'status' => isset($master_status[strval($detail['status_prl'])]) ? $master_status[strval($detail['status_prl'])] : null,
                'dataDPR' => $data_eachpr['dataDPR'],
                'dataDKM' => $data_eachpr['dataDKM'],
                'dataDPS' => $data_eachpr['dataDPS'],
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
        $request = [
            'offset' => ($request['page'] - 1) * $request['limit'],
            'limit' => $request['limit'],
            'variable' => isset($request['keyword']) ? $request['keyword'] : '',
            'status' => 2 // Hardcode for filter by contract name
        ];

        // Validate value offset harus lebih dari 0
        if ($request['offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Try to request
            $raw = Http::withoutVerifying()->post($this->project->url . '/manajemen-kontrak/getDataKontrak', $request)->json();
            $data = $raw['result']['data']['data'];
            // Format response
            $response = [];
            foreach ($data as $key => $dat) {
                $response[] = [
                    'id' => $dat['id_manajemen_kontrak'],
                    'nama_kontrak' => $dat['nama_paket'],
                    'nomor_kontrak' => $dat['no_kontrak'],
                    'nomor_pr' => $dat['pr_kode'],
                    'status' => $dat['nama_status']
                ];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function contractDetail(array $request)
    {
        $request = [
            'id_manajemen_kontrak' => $request['id']
        ];

        try {
            // Try to request
            $raw = Http::withoutVerifying()->post($this->project->url . '/manajemen-kontrak/getDataKontrakById', $request);
            // Get response mentah
            $raw = json_decode($raw->body(), true);
            $data = $raw['result']['data'];
            $data = collect($data)->unique('nama_paket')->first();
            // Validate data is found
            if ($data == null) {
                throw new \Exception("Data tidak ditemukan !", 404);
            }
            // Format response
            $response = [
                'id' => $data['id_manajemen_kontrak'],
                'nama_kontrak' => $data['nama_paket'],
                'nomor_kontrak' => $data['no_kontrak'],
                'nomor_pr' => $data['pr_kode'],
                'status' => $data['nama_status'],
                'nama_perusahaan' => $data['nama_perusahaan'],
                'tipe_kontrak' => $data['tipe_kontrak_nama'],
                'tanggal_mulai' => $data['tgl_mulai_kontrak'],
                'tanggal_selesai' => $data['tgl_selesai_kontrak'],
            ];
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }

    public function contractDocument(array $request)
    {
        $request = [
            'id_manajemen_kontrak' => $request['id'],
            'offset' => ($request['page'] - 1) * $request['limit'],
            'limit' => $request['limit']
        ];

        // Validate value offset harus lebih dari 0
        if ($request['offset'] < 0) {
            throw new \Exception("Parameter page harus diisi minimal 1 !", 422);
        }

        try {
            // Try to request
            $raw = Http::withoutVerifying()->post($this->project->url . '/manajemen-kontrak/getHistoryScanDoc', $request)->json();
            $data = $raw['result']['data']['data'];
            // Format response
            $response = [];
            foreach ($data as $key => $dat) {
                $response[] = [
                    'id' => $dat['id'],
                    'url' => $this->project->url . '/' . $dat['url_scan_kontrak'],
                    'date' => $dat['upload_date'],
                    'uploader' => $dat['nama_pegawai']
                ];
            }
            // Return response
            return $response;
        } catch (\Throwable $e) {
            // Return service error
            $this->serviceError($e);
        }
    }
}
