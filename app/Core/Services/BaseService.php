<?php

namespace Itpi\Core\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Itpi\Models\User;

class BaseService
{
    protected Client $client;
    protected string $app_env;
    protected bool $app_debug;

    // Fill the value of the attributes on construct
    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
        $this->app_env = env('APP_ENV');
        $this->app_debug = env('APP_DEBUG');
    }

    // For throw formated Internal Server Error
    protected function serviceError($e)
    {
        // Error code
        $error_code = ($e->getCode() == 0) ? 500 : $e->getCode();
        // Throw error
        if ($this->app_env != 'production' && $this->app_debug == true) {
            throw new \Exception($e->getMessage(), $error_code);
        } else {
            throw new \Exception('Internal Server Error', $error_code);
        }
    }

    // For checking session status on PHP Project
    protected function phpCheckSession($data)
    {
        // Logout if data return status 404
        if ($data['status'] == 404) {
            // Logout current token
            $user = User::find(Auth::id());
            $user->tokens()->delete();
            // Throw error
            throw new \Exception("Unauthenticated.", 401);
        }
    }

    // For generate vendor status on PHP Project
    protected function phpVendorStatus(array $data): string
    {
        if (
            $data['is_activated'] == true && $data['is_verified'] == true &&
            ($data['tolak_verifikasi'] == false && $data['tolak_verifikasi'] == null)
        ) {
            $status = 'Terverifikasi';
        } else if (
            $data['is_activated'] == true &&
            (
                ($data['is_verified'] == false || $data['is_verified'] == null) &&
                ($data['tolak_verifikasi'] == false || $data['tolak_verifikasi'] == null) &&
                $data['trigger_verified'] == true
            ) &&
            $data['status_approval_id'] == 0
        ) {
            $status = 'Belum Diverifikasi';
        } else if (
            $data['is_activated'] == true &&
            (
                ($data['is_verified'] == false || $data['is_verified'] == null) &&
                ($data['tolak_verifikasi'] == false || $data['tolak_verifikasi'] == null) &&
                $data['trigger_verified'] == true
            ) &&
            $data['status_approval_id'] == 1
        ) {
            $status = 'Proses Approval';
        } else if (
            $data['is_activated'] == true &&
            (
                ($data['is_verified'] == false || $data['is_verified'] == null) &&
                $data['trigger_verified'] == false &&
                $data['tolak_verifikasi'] == true
            )
        ) {
            $status = 'Koreksi';
        } else if (
            $data['is_activated'] == true &&
            (
                ($data['is_verified'] == false || $data['is_verified'] == null) &&
                ($data['trigger_verified'] == false || $data['trigger_verified'] == null) &&
                ($data['tolak_verifikasi'] == false || $data['tolak_verifikasi'] == null)
            )
        ) {
            $status = 'Teraktivasi';
        } else if ($data['is_activated'] == false) {
            $status = 'Dinonaktifkan';
        } else if ($data['is_activated'] == null && $data['is_registered'] == null) {
            $status = 'Belum Diaktifkan';
        } else {
            $status = '';
        }

        // Return status
        return $status;
    }

    public function phpFormatTahapan($data): array
    {
        $response_tahapan = [];
        // Loop
        foreach ($data as $key => $tahap) {
            // Format start date and end date
            $start_date = date('Y-m-d H:i:s', strtotime($tahap['tgl_mulai']));
            $end_date = date('Y-m-d H:i:s', strtotime($tahap['tgl_selesai']));
            // Generate status tahapan
            if ($tahap['tgl_mulai'] != null && $tahap['tgl_selesai'] != null) {
                if (($start_date <= date('Y-m-d H:i:s')) && $end_date <= date('Y-m-d H:i:s')) {
                    $status = 'done';
                } else if (($start_date <= date('Y-m-d H:i:s')) && $end_date > date('Y-m-d H:i:s')) {
                    $status = 'ongoing';
                } else {
                    $status = 'draft';
                }
            } else {
                $status = 'draft';
            }
            $response_tahapan[] = [
                'nama_tahapan' => $tahap['nama_tahapan'],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $status
            ];
        }
        // Return formatted data
        return $response_tahapan;
    }

    // For checking session status on .NET Project
    function netCheckSession($data)
    {
        // Logout if data is null
        if ($data == null) {
            // Logout current token
            $user = User::find(Auth::id());
            $user->tokens()->delete();
            // Throw error
            throw new \Exception("Unauthenticated.", 401);
        }
        // Logout if data response message AUTHORIZATION.FORBIDDEN
        if (isset($data['Message'])) {
            if ($data['Message'] == "AUTHORIZATION.FORBIDDEN") {
                throw new \Exception("Anda tidak memiliki hak akses !", 404);
            }
        }
    }

    // For checking session status on .NET project
    function netVendorStatus(array $data): string
    {
        // Generate status
        if ($data['StatusVend'] == 1) {
            $status = 'Belum Terverifikasi';
        } else if ($data['StatusVend'] == 2) {
            $status = 'Proses Verifikasi';
        } else if ($data['StatusVend'] == 3) {
            $status = 'Terverifikasi';
        } else if ($data['StatusVend'] == 4) {
            $status = 'Tidak Terverifikasi';
        } else {
            $status = '';
        }
        // Return status
        return $status;
    }

    // For translate word on .NET Project
    function netTranslate(string $project, string $word): string
    {
        switch ($project) {
            case 'uoi':
                // Language dictionary
                $lang = [
                    "BREADCRUMB" => [
                        "BERANDA" => "Beranda",
                        "DATA_APPROVAL" => "Data Approval",
                        "DATA_BLACKLIST" => "Data Blacklist",
                        "DATA_BLACKLIST_PRO" => "Data Blacklist Procurement",
                        "DATA_BLACKLIST_VENDOR" => "Data Blacklist Vendor",
                        "DATA_WHITELIST" => "Data Whitelist",
                        "DATA_WHITELIST_PRO" => "Data Whitelist Procurement",
                        "DATA_WHITELIST_VENDOR" => "Data Whitelist Vendor",
                        "VENDOR_DATA" => "Data Vendor"
                    ],
                    "SELECT" => [
                        "SEARCH_BYSTATUS" => "Cari berdasarkan status",
                        "APPROVED" => "Disetujui",
                        "REJECTED" => "Ditolak",
                        "ON_PROCESS" => "On Process",
                        "ALL" => "Semua"
                    ],
                    "DETAIL_BLACK" => "Detail Data Blacklist",
                    "DETAIL_WHITE" => "Detail Data Whitelist",
                    "BLACKLIST_MODULE_VENDOR" => "Vendor",
                    "BLACKLIST_MODULE_MATERIAL" => "Procurement",
                    "Blacklist Vendor" => "Blacklist",
                    "OTHER_PEOPLE" => "Pengurus perusahaan yang lain",
                    "NO_KTP" => "Nomor KTP/Passport",
                    "NO_KTPSTOCK" => "No. KTP/Passport/NPWP",
                    "STOCK_HOLDER" => "Pemegang Saham Perusahaan",
                    "BIRTH_DATE" => "Tanggal Lahir",
                    "CARI_BLACKLIST" => "Cari",
                    "DATA_APPROVAL" => "Data Approval",
                    "CARI_BLACKLIST_NAMA" => "Pencarian Nama Perusahaan",
                    "BLACKLIST_TYPE_YES" => "Blacklist",
                    "BLACKLIST_TYPE_NO" => "Whitelist",
                    "BLACKLIST_FOREVER" => "Selamanya",
                    "BLACKLIST_NO" => "Sementara",
                    "BLACKLIST_ALL" => "Semua",
                    "DOWNLOAD" => "Download",
                    "CANCEL_BLACKLIST" => "Whitelist Vendor",
                    "BLACKLIST1" => " Blacklist",
                    "DETAIL_BLACKLIST" => "Detail Blacklist",
                    "DETAIL_WHITELIST" => "Detail Whitelist",
                    "BLACKLIST" => "Anda yakin ingin melakukan blacklist pada vendor ini?",
                    "BLACKLIST_TITLE" => "Blacklist",
                    "WHITELIST_TITLE" => "Whitelist",
                    "WHITELIST" => "Anda yakin ingin melakukan whitelist pada vendor ini?",
                    "STOCK_COMPANY" => "Perusahaan",
                    "STOCK_PERSONAL" => "Perseorangan",
                    "TABLE" => [
                        "APPROVALDATE" => "Tanggal Approval",
                        "TYPE" => "Tipe",
                        "STOCK_UNIT" => "Unit",
                        "NO" => "No.",
                        "NAMA" => "Nama",
                        "STOCKNAME" => "Nama Pemilik",
                        "STOCKTYPE" => "Tipe Pemilik",
                        "COMPANY" => "Perusahaan",
                        "AMOUNT_PERSON" => "Jumlah Orang Terblacklist",
                        "AMOUNT_MATERIAL" => "Jumlah Material Terblacklist",
                        "DESCRIPTION" => "Keterangan Blacklist",
                        "DESCRIPTIONS" => "Deskripsi",
                        "DOCUMENT" => "Dokumen",
                        "NAME" => "Nama",
                        "POSITION" => "Jabatan",
                        "STOCK" => "Saham",
                        "REMOVE" => "Hapus",
                        "VENDOR_APPROVAL_DATE" => "Tanggal Approve",
                        "PRESIDENT_DIRECTORCOMPANY" => "Nama Perusahaan",
                        "START_DATE" => "Tanggal Mulai",
                        "END_DATE" => "Tanggal Selesai",
                        "APPROVAL" => "Nama Approver",
                        "JABATAN" => "Jabatan",
                        "STATUS" => "Status",
                        "TANGGAL_APPROVE" => "Tanggal Approval"
                    ],
                    "POSITION_PERSON" => [
                        "PRESIDENT_DIRECTOR" => "Presiden Direktur",
                        "DIRECTOR_OF_FINANCE" => "Direktur Keuangan",
                        "DIRECTOR_OF_OPERATIONS" => "Direktur Operasional",
                        "OTHERS" => "Lain-lain"
                    ],
                    "TAMBAH" => "Tambah",
                    "TOTAL_DATA" => "Total Data",
                    "EMPLOYEE_VENDOR" => "Pengurus Perusahaan",
                    "VENDOR_STOCK" => "Pemegang Saham Perusahaan",
                    "DETAIL_PERSON" => "Tambahan nama orang yang diblacklist",
                    "OTHER_DESCRIPTION" => "Keterangan",
                    "UPLOAD" => "Upload Dokumen Pendukung",
                    "DOC_PENDUKUNG" => "Dokumen Pendukung",
                    "UNTIL" => "Hingga",
                    "CANCEL" => "Keluar",
                    "BLACKLIST_USER" => "Blacklist",
                    "BLACKLIST_CONFIRM" => "Yakin akan blacklist ?",
                    "MESSAGE" => [
                        "ERR_API" => "Gagal Akses API",
                        "LOADING" => "Silahkan Tunggu..",
                        "ERR_NOFILE" => "Dokumen Pendukung Blacklist belum dipilih",
                        "ERR_NOFILE_WHITE" => "Dokumen Pendukung Whitelist belum dipilih",
                        "SUCC_SEND_APPROVE" => "Berhasil Kirim Approval",
                        "SEND_APPROVAL_WHITELIST" => "Apakah anda yakin mengirimkan pengajuan approval untuk whitelist vendor ini? ",
                        "EMAIL_SENT" => "Email Terkirim",
                        "FAIL_GET_BLACKLIST" => "Gagal mendapatkan list data blacklist",
                        "CANCEL_BLACKLIST" => "Apakah Anda Yakin untuk Membatalkan Blacklist?",
                        "SUCC_CANCEL_BL" => "Berhasil Membatalkan Blacklist",
                        "FAIL_CANCEL_BL" => "Gagal Membatalkan Blacklist",
                        "ERR_INACTIVATE" => "Gagal Meonaktifkan Data",
                        "SUCC_BL" => "Data yang Berhasil di Blacklist",
                        "SUCC_SAVE_DATA" => "Berhasil simpan data",
                        "ERR_SAVE_DATA" => "Gagal Menyimpan Data",
                        "SUCC_SAVE_BL" => "Berhasil Simpan Data Blacklist",
                        "SUCC_SAVE_BLS" => "Berhasil Simpan Data Blacklist Service",
                        "REF_NOT_EXIST" => "Nomor Referensi Tdak Ada",
                        "NOT_CHOOSE" => "Anda belum memilih tipe blacklist",
                        "NOT_MAT" => "Material Group masih kosong",
                        "NOT_CHOOSEMAT" => "Anda belum memilih Material Group",
                        "NOT_REMARK" => "Keterangan masih belum diisi",
                        "NOT_MASA" => "Masa Blacklist belum dipilih",
                        "NOT_ENDDATE" => "Tanggal Berakhir tidak boleh sebelum atau sama dengan Tanggal Mulai",
                        "START_DATE_NULL" => "Tanggal Mulai belum diisi",
                        "END_DATE_NULL" => "Tanggal Berakhir belum diisi",
                        "ERR_UPLOAD_TYPE" => "Tipe file tidak sesuai",
                        "ERR_UPLOAD_SIZE" => "Ukuran file terlalu besar"
                    ],
                    "STOCK_UNIT_PERCENTAGE" => "Persen",
                    "STOCK_UNIT_SHEET" => "Lembar",
                    "STOCK_UNIT_UNIT" => "Unit",
                    "SEND_TO_APPROVAL" => "Apakah anda yakin mengirimkan pengajuan approval untuk blacklist vendor ini? ",
                    "NO_DATA" => "Tidak Ada Data",
                    "DATA_BELUM" => "Data Belum Ada",
                    "Download Surat Tugas" => "Download Surat Tugas",
                    "Masa Blacklist" => "Masa Blacklist",
                    "POSITION_PERSON:" => [
                        "PRESIDENT_DIRECTOR" => "Presiden Direktur"
                    ],
                    "BTN" => [
                        "CLOSE" => "Keluar",
                        "BACK" => "Kembali",
                        "SAVE" => "Simpan",
                        "SEARCH" => "Cari",
                        "LANJUT" => "Lanjut",
                        "APPROVE" => "Terima",
                        "REJECT" => "Tolak"
                    ],
                    "TUTUP" => "Batal",
                    "REJECT" => "Rejected",
                    "APPROVE" => "Approved",
                    "APPROVAL" => "Detail Approval",
                    "DETAIL_APPROVAL" => "Detail Approval",
                    "CHOOSE_BLACK" => "Pilih Blacklist",
                    "SELECT_MAT" => "Pilih Material",
                    "ADD_MAT" => "Tambah Material",
                    "SEND_APPROVAL" => "Kirim ke Approver",
                    "APPROVE_WHITELIST" => "Apakah anda yakin menyetujui whitelist vendor ini?",
                    "APPROVE_BLACKLIST" => "Apakah anda yakin menyetujui blacklist vendor ini?"
                ];
                // Translate words
                if (isset($lang[$word])) {
                    return $lang[$word];
                } else {
                    return $word;
                }
                break;

            default:
                return $word;
                break;
        }
    }
}
