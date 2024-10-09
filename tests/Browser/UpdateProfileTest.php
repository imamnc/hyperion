<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateProfileTest extends DuskTestCase
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
     * @group profile
     * @group profile.update_profile
     * @return void
     */
    public function test_update_profile_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run change photo success
            $browser->loginAs($user)
                ->visitRoute('profile')
                ->pause(500)
                ->type('name', 'Namanya Diedit')
                ->type('email', 'edited@mail.dev')
                ->click('@submit-edit-profile')
                ->assertRouteIs('profile')
                ->assertSee('Data profile diperbarui'); // Check success message text
        });
    }

    /**
     * @group profile
     * @group profile.update_profile
     * @return void
     */
    public function test_update_profile_validation()
    {
        // Find user
        $user = User::find(1);

        // Create new admin
        $new_admin = User::create([
            'name' => 'Admin Baru',
            'email' => 'adminnew@mail.dev',
            'password' => Hash::make('12345678'),
            'type' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($user, $new_admin) {
            // Run required validation test for name & email
            $browser->loginAs($user)
                ->visitRoute('profile')
                ->pause(500)
                ->type('name', '')
                ->type('email', '')
                ->click('@submit-edit-profile')
                ->assertRouteIs('profile')
                ->assertSee('The name field is required.') // Check required validation error text
                ->assertSee('The email field is required.'); // Check required validation error text

            // Run unique email validation
            $browser->type('name', 'Administrator')
                ->type('email', $new_admin->email)
                ->click('@submit-edit-profile')
                ->assertRouteIs('profile')
                ->assertSee('The email has already been taken.'); // Check unique email validation error text
        });
    }

    /**
     * @group profile
     * @group profile.update_password
     * @return void
     */
    public function test_update_password_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run change photo success
            $browser->loginAs($user)
                ->visitRoute('profile')
                ->pause(1000)
                ->click('@btn-tab-edit-password')
                ->type('current_password', '12345678')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->click('@submit-edit-password')
                ->assertRouteIs('profile')
                ->assertSee('Password telah diubah'); // Check success message text
        });
    }

    /**
     * @group profile
     * @group profile.update_password
     * @return void
     */
    public function test_update_password_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run change password with blank form
            $browser->loginAs($user)
                ->visitRoute('profile')
                ->pause(500)
                ->click('@btn-tab-edit-password')
                ->type('current_password', '')
                ->type('password', '')
                ->type('password_confirmation', '')
                ->click('@submit-edit-password')
                ->assertRouteIs('profile')
                ->assertSee("The current password field is required.") // Check validation error message text
                ->assertSee("The password field is required.") // Check validation error message text
                ->assertSee("The password confirmation field is required."); // Check validation error message text

            // Run change password with wrong current password
            $browser->type('current_password', 'passwordsalah')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->click('@submit-edit-password')
                ->assertRouteIs('profile')
                ->assertSee("Current password doesn't match"); // Check validation error message text

            // Run change password with not match password confirmation
            $browser->type('current_password', 'passwordsalah')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password1234')
                ->click('@submit-edit-password')
                ->assertRouteIs('profile')
                ->assertSee("The password confirmation does not match."); // Check validation error message text
        });
    }
}
