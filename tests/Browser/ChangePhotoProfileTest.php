<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ChangePhotoProfileTest extends DuskTestCase
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
     * @group profile.change_photo
     * @return void
     */
    public function test_change_photo_profile_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run change photo success
            $browser->loginAs($user)
                ->visitRoute('profile')
                ->pause(1000)
                ->attach('#profile_photo', public_path('/img/avatar.png')) // Attach file to upload
                ->waitFor('#modal-crop.show')
                ->whenAvailable('#modal-crop.show', function ($modal) {
                    $modal->press('Save');
                })
                ->waitForText('Foto profile disimpan')
                ->assertSee('Foto profile disimpan');
        });
    }
}
