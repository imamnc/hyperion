<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminUpdateTest extends DuskTestCase
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
     * @group admin.update
     * @return void
     */
    public function test_update_admin_success()
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
            // Run valid edit admin test
            $browser->loginAs($user)
                ->visitRoute('admin')
                ->waitFor("#btn-edit-$admin_new->id")
                ->click("#btn-edit-$admin_new->id")
                ->waitFor('#edit-admin-modal.show')
                ->whenAvailable('#edit-admin-modal.show', function ($modal) {
                    $modal->type('name', 'Admin Testing')
                        ->type('email', 'admintest@mail.dev')
                        ->click('@submit-edit-admin');
                })
                ->waitForText('Perubahan data admin telah disimpan')
                ->assertSee('Perubahan data admin telah disimpan'); // Check success status text
        });
    }

    /**
     * @group admin
     * @group admin.update
     * @return void
     */
    public function test_update_admin_validation()
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
            // Run required name validation test
            $browser->loginAs($user)
                ->visitRoute('admin')
                ->waitFor("#btn-edit-$admin_new->id")
                ->click("#btn-edit-$admin_new->id")
                ->waitFor('#edit-admin-modal.show')
                ->whenAvailable('#edit-admin-modal.show', function ($modal) {
                    $modal->type('name', '')
                        ->type('email', 'admintest@mail.dev')
                        ->click('@submit-edit-admin');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->waitForText('The name field is required.')
                ->assertSee('The name field is required.'); // Check success status text

            // Run required email validation test
            $browser->waitFor('#edit-admin-modal.show')
                ->whenAvailable('#edit-admin-modal.show', function ($modal) {
                    $modal->type('name', 'Admin Testing')
                        ->type('email', '')
                        ->click('@submit-edit-admin');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->waitForText('The email field is required.')
                ->assertSee('The email field is required.'); // Check validation error message

            // Run unique email validation test
            $browser->waitFor('#edit-admin-modal.show')
                ->whenAvailable('#edit-admin-modal.show', function ($modal) {
                    $modal->type('name', 'Admin Testing')
                        ->type('email', 'admin@mail.dev')
                        ->click('@submit-edit-admin');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->waitForText('The email has already been taken.')
                ->assertSee('The email has already been taken.'); // Check validation error message
        });
    }
}
