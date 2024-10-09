<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminCreateTest extends DuskTestCase
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
     * @group admin
     * @group admin.create
     * @return void
     */
    public function test_create_admin_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid create admin test
            $browser->loginAs($user)
                ->visitRoute('admin')
                ->click('@btn-create-admin')
                ->waitFor('#add-admin-modal.show')
                ->whenAvailable('#add-admin-modal.show', function ($modal) {
                    $modal->type('name', 'Admin Testing')
                        ->type('email', 'admintest@mail.dev')
                        ->click('@submit-create-admin');
                })
                ->waitForText('Admin baru telah dibuat')
                ->assertSee('Admin baru telah dibuat'); // Check success status text
        });
    }

    /**
     * @group admin
     * @group admin.create
     * @return void
     */
    public function test_form_validation_create_admin()
    {
        // Find user
        $user = User::find(1);

        // Create new admin
        $admin_new = User::create([
            'name' => 'Administrator Test',
            'email' => 'admintesting@mail.dev',
            'password' => Hash::make('12345678'),
            'type' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($user, $admin_new) {
            // 1. Run name required validation test
            $browser->loginAs($user)
                ->visitRoute('admin')
                ->click('@btn-create-admin')
                ->waitFor('#add-admin-modal.show')
                ->whenAvailable('#add-admin-modal.show', function ($modal) {
                    $modal->type('name', '')
                        ->type('email', 'admintest@mail.dev')
                        ->click('@submit-create-admin');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->waitForText('The name field is required.')
                ->assertSee('The name field is required.'); // Check for name invalid message

            // Run email required validation test
            $browser
                ->whenAvailable('#add-admin-modal.show', function ($modal) {
                    $modal->type('name', 'Admin Testing')
                        ->type('email', '')
                        ->click('@submit-create-admin');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->waitForText('The email field is required.')
                ->assertSee('The email field is required.'); // Check for email invalid message

            // Run unique email validation test
            $browser
                ->whenAvailable('#add-admin-modal.show', function ($modal) use ($admin_new) {
                    $modal->type('name', 'Admin Testing')
                        ->type('email', $admin_new->email)
                        ->click('@submit-create-admin');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->waitForText('The email has already been taken.')
                ->assertSee('The email has already been taken.'); // Check for email invalid message
        });
    }
}
