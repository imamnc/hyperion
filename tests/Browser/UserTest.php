<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
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
     * @group user
     * @group user.reset_pin
     * @return void
     */
    public function test_reset_pin_user_success()
    {
        // Find user
        $user = User::find(1);

        // Create new user and save session ID
        $user_new = User::create([
            'project_id' => 1,
            'name' => 'admindeveloper',
            'email' => 'admindeveloper',
            'service_token' => '7aj29al201001kia88j32829aa59ks',
            'pin' => Hash::make('123456'),
            'type' => 'user'
        ]);

        $this->browse(function (Browser $browser) use ($user, $user_new) {
            // Run valid update user test
            $browser->loginAs($user)
                ->visitRoute('user')
                ->waitFor("#btn-reset-$user_new->id")
                ->click("#btn-reset-$user_new->id") // Click reset button
                ->waitFor('.swal2-popup')
                ->whenAvailable('.swal2-popup', function ($modal) {
                    $modal->click('.swal2-confirm'); // Click alert confirm button
                })
                ->waitForText('PIN user telah direset')
                ->assertSee('PIN user telah direset'); // Check success message text
        });
    }

    /**
     * @group user
     * @group user.delete
     * @return void
     */
    public function test_delete_user_success()
    {
        // Find user
        $user = User::find(1);

        // Create new user and save session ID
        $user_new = User::create([
            'project_id' => 1,
            'name' => 'admindeveloper',
            'email' => 'admindeveloper',
            'service_token' => '7aj29al201001kia88j32829aa59ks',
            'pin' => Hash::make('123456'),
            'type' => 'user'
        ]);

        $this->browse(function (Browser $browser) use ($user, $user_new) {
            // Run valid update user test
            $browser->loginAs($user)
                ->visitRoute('user')
                ->waitFor("#btn-delete-$user_new->id")
                ->click("#btn-delete-$user_new->id") // Click delete button
                ->waitFor('.swal2-popup')
                ->whenAvailable('.swal2-popup', function ($modal) {
                    $modal->click('.swal2-confirm'); // Click alert confirm button
                })
                ->waitForText('Data user telah dihapus')
                ->assertSee('Data user telah dihapus'); // Check success message text
        });
    }
}
