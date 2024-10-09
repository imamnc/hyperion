<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminDeleteTest extends DuskTestCase
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
     * @group admin.delete
     * @return void
     */
    public function test_delete_admin_success()
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
                ->waitFor("#btn-delete-$admin_new->id")
                ->click("#btn-delete-$admin_new->id")
                ->waitFor('.swal2-popup')
                ->whenAvailable('.swal2-popup', function ($modal) {
                    $modal->click('.swal2-confirm'); // Click alert confirm button
                })
                ->waitForText('Data admin telah dihapus')
                ->assertSee('Data admin telah dihapus'); // Check success message text
        });
    }
}
