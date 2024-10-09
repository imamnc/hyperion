<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SettingsTest extends DuskTestCase
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
     * @group settings
     * @group settings.general
     * @return void
     */
    public function test_save_general_settings_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid general settings save
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->type('app_version', '2.0.0')
                ->type('assets_version', '2.0')
                ->click("@submit-general-settings") // Click save button
                ->waitForText('Perubahan data settings telah disimpan')
                ->assertSee('Perubahan data settings telah disimpan') // Check success message text
                ->assertRouteIs('settings');
        });
    }

    /**
     * @group settings
     * @group settings.general
     * @return void
     */
    public function test_save_general_settings_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid general settings save
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->type('app_version', '')
                ->type('assets_version', '')
                ->click("@submit-general-settings") // Click save button
                ->waitForText('The app version field is required.')
                ->assertSee('The app version field is required.') // Check validation error message text
                ->waitForText('The assets version field is required.')
                ->assertSee('The assets version field is required.') // Check validation error message text
                ->assertRouteIs('settings');
        });
    }

    /**
     * @group settings
     * @group settings.security
     * @return void
     */
    public function test_save_security_settings_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid general settings save
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-security')
                ->pause(500)
                ->type('password_default', 'password123')
                ->type('pin_default', '123455')
                ->click("@submit-security-settings") // Click save button
                ->waitForText('Perubahan data settings telah disimpan')
                ->assertSee('Perubahan data settings telah disimpan') // Check success message text
                ->assertRouteIs('settings');
        });
    }

    /**
     * @group settings
     * @group settings.security
     * @return void
     */
    public function test_save_security_settings_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run required validation
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-security')
                ->pause(500)
                ->type('password_default', '')
                ->type('pin_default', '')
                ->click("@submit-security-settings") // Click save button
                ->waitForText('The password default field is required.')
                ->assertSee('The password default field is required.') // Check validation error message text
                ->waitForText('The pin default field is required.')
                ->assertSee('The pin default field is required.') // Check validation error message text
                ->assertRouteIs('settings');

            // Run min length 8 for password
            $browser->type('password_default', '123456')
                ->click("@submit-security-settings") // Click save button
                ->waitForText('The password default must be at least 8 characters.')
                ->assertSee('The password default must be at least 8 characters.') // Check validation error message text
                ->assertRouteIs('settings');
        });
    }

    /**
     * @group settings
     * @group settings.feature
     * @return void
     */
    public function test_create_features_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run create feature test
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-features')
                ->pause(500)
                ->click('@btn-add-feature')
                ->waitFor('#add-feature-modal.show')
                ->whenAvailable('#add-feature-modal.show', function ($modal) {
                    $modal->type('name', 'Fitur Testing')
                        ->type('code', 'test')
                        ->click('@submit-add-feature');
                })
                ->waitForText('Fitur baru telah ditambahkan')
                ->assertSee('Fitur baru telah ditambahkan');
        });
    }

    /**
     * @group settings
     * @group settings.feature
     * @return void
     */
    public function test_create_features_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run required validation test
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-features')
                ->pause(500)
                ->click('@btn-add-feature')
                ->waitFor('#add-feature-modal.show')
                ->whenAvailable('#add-feature-modal.show', function ($modal) {
                    $modal->type('name', '') // Empty field name
                        ->type('code', '') // Empty field code
                        ->click('@submit-add-feature');
                })
                ->waitForText('The name field is required.')
                ->assertSee('The name field is required.') // Check validation error message
                ->waitForText('The code field is required.')
                ->assertSee('The code field is required.'); // Check validation error message

            // Run unique validation test
            $browser->waitFor('#add-feature-modal.show')
                ->whenAvailable('#add-feature-modal.show', function ($modal) {
                    $modal->type('name', 'Manajemen Blacklist') // Fill name with already taken name
                        ->type('code', 'blacklist') // Fill code with already taken code
                        ->click('@submit-add-feature');
                })
                ->waitForText('The name has already been taken.')
                ->assertSee('The name has already been taken.') // Check validation error message
                ->waitForText('The code has already been taken.')
                ->assertSee('The code has already been taken.'); // Check validation error message
        });
    }

    /**
     * @group settings
     * @group settings.feature
     * @return void
     */
    public function test_update_features_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run edit feature test
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-features')
                ->pause(500)
                ->waitFor('#opsi-1')
                ->click('#opsi-1')
                ->waitFor('#opsi-1.show')
                ->click('#btn-edit-1')
                ->waitFor('#edit-feature-modal.show')
                ->whenAvailable('#edit-feature-modal.show', function ($modal) {
                    $modal->type('name', 'Fitur Testing')
                        ->type('code', 'test')
                        ->click('@submit-edit-feature');
                })
                ->waitForText('Perubahan data fitur telah disimpan')
                ->assertSee('Perubahan data fitur telah disimpan');
        });
    }

    /**
     * @group settings
     * @group settings.feature
     * @return void
     */
    public function test_update_features_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run required validation test
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-features')
                ->pause(500)
                ->waitFor('#opsi-1')
                ->click('#opsi-1')
                ->waitFor('#opsi-1.show')
                ->click('#btn-edit-1')
                ->waitFor('#edit-feature-modal.show')
                ->whenAvailable('#edit-feature-modal.show', function ($modal) {
                    $modal->type('name', '') // Empty field name
                        ->type('code', '') // Empty field code
                        ->click('@submit-edit-feature');
                })
                ->waitForText('The name field is required.')
                ->assertSee('The name field is required.') // Check validation error message
                ->waitForText('The code field is required.')
                ->assertSee('The code field is required.'); // Check validation error message

            // Run unique validation test
            $browser->waitFor('#edit-feature-modal.show')
                ->whenAvailable('#edit-feature-modal.show', function ($modal) {
                    $modal->type('name', 'Manajemen Blacklist') // Fill name with already taken name
                        ->type('code', 'blacklist') // Fill code with already taken code
                        ->click('@submit-edit-feature');
                })
                ->waitForText('The name has already been taken.')
                ->assertSee('The name has already been taken.') // Check validation error message
                ->waitForText('The code has already been taken.')
                ->assertSee('The code has already been taken.'); // Check validation error message
        });
    }

    /**
     * @group settings
     * @group settings.features
     * @return void
     */
    public function test_delete_feature_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid delete feature test
            $browser->loginAs($user)
                ->visitRoute('settings')
                ->click('@btn-tab-features')
                ->pause(500)
                ->waitFor('#opsi-1')
                ->click('#opsi-1')
                ->waitFor('#btn-delete-1')
                ->click('#btn-delete-1') // Click delete button
                ->waitFor('.swal2-popup')
                ->whenAvailable('.swal2-popup', function ($modal) {
                    $modal->click('.swal2-confirm'); // Click alert confirm button
                })
                ->waitForText('Data fitur telah dihapus')
                ->assertSee('Data fitur telah dihapus'); // Check success message text
        });
    }
}
