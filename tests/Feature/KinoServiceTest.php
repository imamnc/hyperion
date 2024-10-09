<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KinoServiceTest extends TestCase
{
    /*==========================================================================
     LOGIN
    ==========================================================================*/
    /**
     * Endpoint login success test.
     * @group kino
     * @group kino.login
     * @return void
     */
    public function test_kino_login_success()
    {
        // Make request data
        $request = [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ];
        // Http request
        $response = $this->withHeader('Accept', 'application/json')->post('/api/login', $request);
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data' => ['token']]);
    }

    /**
     * Endpoint login with invalid payload test.
     * @group kino
     * @group kino.login
     * @return void
     */
    public function test_kino_login_with_invalid_payload()
    {
        // Http request with null value of request data
        $null_request_response = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => null,
            'email' => null,
            'password' => null
        ]);
        // Check response
        $null_request_response->assertUnprocessable()->assertJsonStructure(['message']);

        // Http request with invalid username/password request data
        $invalid_credentials_request_response = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => 'admin', // Wrong username
            'password' => '123' // Wrong password
        ]);
        // Check response
        $invalid_credentials_request_response->assertUnprocessable()
            ->assertJsonStructure(['message'])
            ->assertJsonFragment(['message' => 'Email atau Password salah !']);
    }

    /**
     * Endpoint login with no accept json header test.
     * @group kino
     * @group kino.login
     * @return void
     */
    public function test_kino_login_with_no_accept_json_header()
    {
        // Http request with no Accept header (application/json)
        $response = $this->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }


    /*==========================================================================
     GET USER DETAIL
    ==========================================================================*/
    /**
     * Endpoint get user detail test.
     * @group kino
     * @group kino.user_detail
     * @return void
     */
    public function test_kino_get_user_detail()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/user');
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get user detail with no accept json header test.
     * @group kino
     * @group kino.user_detail
     * @return void
     */
    public function test_kino_get_user_detail_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')->withToken($user['data']['token'])->get('/api/user');
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get user detail without token test.
     * @group kino
     * @group kino.user_detail
     * @return void
     */
    public function test_kino_get_user_detail_without_token()
    {
        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')->get('/api/user');
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET MENUS
    ==========================================================================*/
    /**
     * Endpoint get menu test.
     * @group kino
     * @group kino.menu
     * @return void
     */
    public function test_kino_get_menu()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/user/menu');
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get menu with no accept json header test.
     * @group kino
     * @group kino.menu
     * @return void
     */
    public function test_kino_get_menu_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')->withToken($user['data']['token'])->get('/api/user/menu');
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get menu without token test.
     * @group kino
     * @group kino.menus
     * @return void
     */
    public function test_kino_get_menu_without_token()
    {
        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')->get('/api/user/menu');
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET VENDOR LIST
    ==========================================================================*/
    /**
     * Endpoint get vendor list test.
     * @group kino
     * @group kino.vendor_list
     * @return void
     */
    public function test_kino_get_vendor_list()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10 // required|int
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/vendor?' . http_build_query($request));
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint login with invalid payload test.
     * @group kino
     * @group kino.vendor_list
     * @return void
     */
    public function test_kino_get_vendor_list_with_invalid_payload()
    {
        // Http request login
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request with no Accept header (application/json)
        $request_no_payload = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/vendor');
        // Check request_no_payload
        $request_no_payload->assertStatus(422)
            ->assertJsonFragment(['message' => 'Payload request tidak sesuai !'])
            ->assertJsonStructure(['message']);

        // Http request with invalid parameter request 'page'
        $request_invalid_page = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/vendor?' . http_build_query([
                'keyword' => null, // nullable|string
                'page' => 0, // required|int|min:1
                'limit' => 10 // required|int
            ]));
        // Check request_invalid_page
        $request_invalid_page->assertStatus(422)
            ->assertJsonFragment(['message' => 'Parameter page harus diisi minimal 1 !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get menu with no accept json header test.
     * @group kino
     * @group kino.vendor_list
     * @return void
     */
    public function test_kino_get_vendor_list_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')
            ->withToken($user['data']['token'])
            ->get('/api/vendor?' . http_build_query([
                'keyword' => null, // nullable|string
                'page' => 0, // required|int|min:1
                'limit' => 10 // required|int
            ]));
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get menu without token test.
     * @group kino
     * @group kino.vendor_list
     * @return void
     */
    public function test_kino_get_vendor_list_without_token()
    {
        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->get('/api/vendor?' . http_build_query([
                'keyword' => null, // nullable|string
                'page' => 0, // required|int|min:1
                'limit' => 10 // required|int
            ]));
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET BLACKLIST
    ==========================================================================*/
    /**
     * Endpoint get blacklist test.
     * @group kino
     * @group kino.blacklist
     * @return void
     */
    public function test_kino_get_blacklist()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'search' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int,
            'flag_status' => 'true' // required|string
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/vendor/blacklist?' . http_build_query($request));
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get blacklist with invalid payload test.
     * @group kino
     * @group kino.blacklist
     * @return void
     */
    public function test_kino_get_blacklist_with_invalid_payload()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request without payload
        $request_without_payload = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/vendor/blacklist');
        // Check request_without_payload
        $request_without_payload->assertStatus(422)
            ->assertJsonFragment(['message' => 'Payload request tidak sesuai !'])
            ->assertJsonStructure(['message']);

        // Http request with invalid parameter 'page'
        $request_invalid_page = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/vendor/blacklist?' . http_build_query([
                'search' => null, // nullable|string
                'page' => 0, // required|int|min:1
                'limit' => 10, // required|int,
                'flag_status' => 'true' // required|string
            ]));
        // Check request_invalid_page
        $request_invalid_page->assertStatus(422)
            ->assertJsonFragment(['message' => 'Parameter page harus diisi minimal 1 !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get blacklist with no accept json header test.
     * @group kino
     * @group kino.blacklist
     * @return void
     */
    public function test_kino_get_blacklist_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')
            ->withToken($user['data']['token'])
            ->get('/api/vendor/blacklist?' . http_build_query([
                'search' => null, // nullable|string
                'page' => 1, // required|int|min:1
                'limit' => 10, // required|int,
                'flag_status' => 'true' // required|string
            ]));
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint blacklist without token test.
     * @group kino
     * @group kino.blacklist
     * @return void
     */
    public function test_kino_get_blacklist_without_token()
    {
        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->get('/api/vendor/blacklist?' . http_build_query([
                'search' => null, // nullable|string
                'page' => 1, // required|int|min:1
                'limit' => 10, // required|int,
                'flag_status' => 'true' // required|string
            ]));
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET PENGADAAN LIST
    ==========================================================================*/
    /**
     * Endpoint get pengadaan list test.
     * @group kino
     * @group kino.pengadaan_list
     * @return void
     */
    public function test_kino_get_pengadaan_list()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int
            'tipe_pengadaan' => 'etender' // required|string
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan?' . http_build_query($request));
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get pengadaan list test.
     * @group kino
     * @group kino.pengadaan_list
     * @return void
     */
    public function test_kino_get_pengadaan_list_with_invalid_payload()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http requeest without payload
        $request_without_payload = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan');
        // Check request_without_payload
        $request_without_payload->assertStatus(422)
            ->assertJsonFragment(['message' => 'Payload request tidak sesuai !'])
            ->assertJsonStructure(['message']);

        // Http requeest with invalid parameter 'page'
        $request_invalid_page = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan?' . http_build_query([
                'keyword' => null, // nullable|string
                'page' => 0, // required|int|min:1
                'limit' => 10, // required|int
                'tipe_pengadaan' => 'etender' // required|string
            ]));
        // Check request_invalid_page
        $request_invalid_page->assertStatus(422)
            ->assertJsonFragment(['message' => 'Parameter page harus diisi minimal 1 !'])
            ->assertJsonStructure(['message']);

        // Http request with invalid parameter 'tipe_pengadaan'
        $request_invalid_tipe_pengadaan = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan?' . http_build_query([
                'keyword' => null, // nullable|string
                'page' => 1, // required|int|min:1
                'limit' => 10, // required|int
                'tipe_pengadaan' => 'etilang' // required|string
            ]));
        // Check request_invalid_tipe_pengadaan
        $request_invalid_tipe_pengadaan->assertStatus(422)
            ->assertJsonFragment(['message' => 'Tipe pengadaan tidak valid !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pengadaan list with no accept header json test.
     * @group kino
     * @group kino.pengadaan_list
     * @return void
     */
    public function test_kino_get_pengadaan_list_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int
            'tipe_pengadaan' => 'etender' // required|string
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan?' . http_build_query($request));
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pengadaan list without token test.
     * @group kino
     * @group kino.pengadaan_list
     * @return void
     */
    public function test_kino_get_pengadaan_list_without_token()
    {
        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int
            'tipe_pengadaan' => 'etender' // required|string
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')->get('/api/pengadaan?' . http_build_query($request));
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET PENGADAAN DETAIL
    ==========================================================================*/
    /**
     * Endpoint get pengadaan detail test.
     * @group kino
     * @group kino.pengadaan_detail
     * @return void
     */
    public function test_kino_get_pengadaan_detail()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'paket_lelang_id' => "216", // nullable|string
            'tipe_pengadaan' => 'etender' // required|string
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get pengadaan detail test.
     * @group kino
     * @group kino.pengadaan_detail
     * @return void
     */
    public function test_kino_get_pengadaan_detail_with_invalid_payload()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request without payload
        $request_without_payload = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan/detail');
        // Check request_without_payload
        $request_without_payload->assertStatus(422)
            ->assertJsonFragment(['message' => 'Payload request tidak sesuai !'])
            ->assertJsonStructure(['message']);

        // Http request with invalid parameter 'paket_lelang_id'
        $request_invalid_paket_lelang_id = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan/detail?' . http_build_query([
                'paket_lelang_id' => "2022", // nullable|string
                'tipe_pengadaan' => 'etender' // required|string
            ]));
        // Check request_invalid_paket_lelang_id
        $request_invalid_paket_lelang_id->assertStatus(422)
            ->assertJsonFragment(['message' => 'Paket lelang ID tidak valid !'])
            ->assertJsonStructure(['message']);

        // Http request with invalid parameter 'tipe_pengadaan'
        $request_invalid_tipe_pengadaan = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan/detail?' . http_build_query([
                'paket_lelang_id' => "216", // nullable|string
                'tipe_pengadaan' => 'etilang' // required|string
            ]));
        // Check request_invalid_tipe_pengadaan
        $request_invalid_tipe_pengadaan->assertStatus(422)
            ->assertJsonFragment(['message' => 'Tipe pengadaan tidak valid !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pengadaan detail test.
     * @group kino
     * @group kino.pengadaan_detail
     * @return void
     */
    public function test_kino_get_pengadaan_detail_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'paket_lelang_id' => "216", // nullable|string
            'tipe_pengadaan' => 'etender' // required|string
        ];
        // Http request with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')
            ->withToken($user['data']['token'])
            ->get('/api/pengadaan/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pengadaan detail without token test.
     * @group kino
     * @group kino.pengadaan_detail
     * @return void
     */
    public function test_kino_get_pengadaan_detail_without_token()
    {
        // Make request data
        $request = [
            'paket_lelang_id' => "216", // nullable|string
            'tipe_pengadaan' => 'etender' // required|string
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')->get('/api/pengadaan/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET PR LIST
    ==========================================================================*/
    /**
     * Endpoint get pr list test.
     * @group kino
     * @group kino.pr_list
     * @return void
     */
    public function test_kino_get_pr_list()
    {
        // Http request login
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int
        ];
        // Http request
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pr?' . http_build_query($request));
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get pr list with invalid payload test.
     * @group kino
     * @group kino.pr_list
     * @return void
     */
    public function test_kino_get_pr_list_with_invalid_payload()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request without payload
        $request_without_payload = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pr');
        // Check request_without_payload
        $request_without_payload->assertStatus(422)
            ->assertJsonFragment(['message' => 'Payload request tidak sesuai !'])
            ->assertJsonStructure(['message']);

        // Http request with invalid parameter 'page'
        $request_invalid_page = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pr?' . http_build_query([
                'keyword' => null, // nullable|string
                'page' => 0, // required|int|min:1
                'limit' => 10, // required|int
            ]));
        // Check request_invalid_page
        $request_invalid_page->assertStatus(422)
            ->assertJsonFragment(['message' => 'Parameter page harus diisi minimal 1 !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pr list with no accept json header test.
     * @group kino
     * @group kino.pr_list
     * @return void
     */
    public function test_kino_get_pr_list_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'text/html')
            ->withToken($user['data']['token'])
            ->get('/api/pr?' . http_build_query($request));
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pr list without token.
     * @group kino
     * @group kino.pr_list
     * @return void
     */
    public function test_kino_get_pr_list_without_token()
    {
        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // required|int|min:1
            'limit' => 10, // required|int
        ];
        // Http requeest with no Accept header (application/json)
        $response = $this->withHeader('Accept', 'application/json')->get('/api/pr?' . http_build_query($request));
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET PR DETAIL
    ==========================================================================*/
    /**
     * Endpoint get pr detail test.
     * @group kino
     * @group kino.pr_detail
     * @return void
     */
    public function test_kino_get_pr_detail()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'nomor_pr' => "PR221110001238", // nullable|string
        ];
        // Http request get detail pr
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pr/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'success'])
            ->assertJsonStructure(['message', 'data']);
    }

    /**
     * Endpoint get pr detail with invalid payload test.
     * @group kino
     * @group kino.pr_detail
     * @return void
     */
    public function test_kino_get_pr_detail_with_invalid_payload()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Http request get detail pr without payload
        $request_without_payload = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pr/detail');
        // Check request_without_payload
        $request_without_payload->assertStatus(422)
            ->assertJsonFragment(['message' => 'Payload request tidak sesuai !'])
            ->assertJsonStructure(['message']);

        // Http request get detail pr without payload
        $request_with_invalid_nomor_pr = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/pr/detail?' . http_build_query([
                'nomor_pr' => "invalidnomorpr-001", // required|string
            ]));
        // Check request_with_invalid_nomor_pr
        $request_with_invalid_nomor_pr->assertStatus(404)
            ->assertJsonFragment(['message' => 'Data PR tidak ditemukan !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pr detail with no accept json header test.
     * @group kino
     * @group kino.pr_detail
     * @return void
     */
    public function test_kino_get_pr_detail_with_no_accept_json_header()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'nomor_pr' => "PR221110001238", // nullable|string
        ];
        // Http request get detail pr
        $response = $this->withHeader('Accept', 'text/html')
            ->withToken($user['data']['token'])
            ->get('/api/pr/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(406)
            ->assertJsonFragment(['message' => 'Content not accepted !'])
            ->assertJsonStructure(['message']);
    }

    /**
     * Endpoint get pr detail without token test.
     * @group kino
     * @group kino.pr_detail
     * @return void
     */
    public function test_kino_get_pr_detail_without_token()
    {
        // Make request data
        $request = [
            'nomor_pr' => "PR221110001238", // nullable|string
        ];
        // Http request get detail pr
        $response = $this->withHeader('Accept', 'application/json')
            ->get('/api/pr/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET CONTRACT LIST
    ==========================================================================*/
    /**
     * Endpoint get contract list test.
     * @group kino
     * @group kino.contract_list
     * @return void
     */
    public function test_kino_get_contract_list()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'keyword' => null, // nullable|string
            'page' => 1, // nullable|string
            'limit' => 10, // nullable|string
        ];
        // Http request get detail pr
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/contract?' . http_build_query($request));
        // Check response
        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Fitur tidak tersedia pada service ini !'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET CONTRACT DETAIL
    ==========================================================================*/
    /**
     * Endpoint get contract detail test.
     * @group kino
     * @group kino.contract_detail
     * @return void
     */
    public function test_kino_get_contract_detail()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'id' => 2, // nullable|int
        ];
        // Http request get detail pr
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/contract/detail?' . http_build_query($request));
        // Check response
        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Fitur tidak tersedia pada service ini !'])
            ->assertJsonStructure(['message']);
    }

    /*==========================================================================
     GET CONTRACT DOCUMENTS
    ==========================================================================*/
    /**
     * Endpoint get contract document test.
     * @group kino
     * @group kino.contract_document
     * @return void
     */
    public function test_kino_get_contract_documents()
    {
        // Http request
        $response_login = $this->withHeader('Accept', 'application/json')->post('/api/login', [
            'project' => 'kino',
            'email' => env('KINO_USERNAME'),
            'password' => env('KINO_PASSWORD')
        ]);
        $user = $response_login->json();

        // Make request data
        $request = [
            'id' => 2, // nullable|int
            'page' => 1, // nullable|int
            'limit' => 10, // nullable|int
        ];
        // Http request get document pr
        $response = $this->withHeader('Accept', 'application/json')
            ->withToken($user['data']['token'])
            ->get('/api/contract/documents?' . http_build_query($request));
        // Check response
        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Fitur tidak tersedia pada service ini !'])
            ->assertJsonStructure(['message']);
    }
}
