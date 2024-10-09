<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /*
    * Setup migrate & seed
    */
    public function setUp(): void
    {
        // Parent Setup
        parent::setUp();
        // Migrate & Seed Database Test
        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');
    }

    /**
     * @group login
     * @return void
     */
    public function test_login_with_valid_credentials()
    {
        // Register new user
        $user = User::create([
            'name' => 'Administrator Testing',
            'email' => 'admintest@mail.dev',
            'password' => Hash::make('12345678'),
            'type' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid login test
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', '12345678')
                ->clickAndWaitForReload('@btn-login')
                ->assertRouteIs('home') // Check that user redirected to route home after login
                ->assertSee("Hi $user->name,"); // Check panel home page has greet text that contains the logged user name

            // Logout after run test
            $browser->logout();
        });
    }

    /**
     * @group login
     * @return void
     */
    public function test_login_with_no_credentials()
    {
        $this->browse(function (Browser $browser) {
            // Run no credentials login test
            $browser->visit('/login')
                ->clickAndWaitForReload('@btn-login')
                ->assertRouteIs('login') // Check that user redirected back to login
                ->assertSee("The email field is required.") // Check email validation error message
                ->assertSee("The password field is required."); // Check password validation error message

            // Logout after run test
            $browser->logout();
        });
    }

    /**
     * @group login
     * @return void
     */
    public function test_login_with_wrong_credentials()
    {
        $this->browse(function (Browser $browser) {
            // Run valid login test
            $browser->visit('/login')
                ->type('email', 'wrong@mail.dev') // Wrong email
                ->type('password', '12345678')
                ->clickAndWaitForReload('@btn-login')
                ->assertRouteIs('login') // Check that user redirected to route home after login
                ->assertSee("These credentials do not match our records."); // Check validation error message

            // Logout after run test
            $browser->logout();
        });
    }
}
