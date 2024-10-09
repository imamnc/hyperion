<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProjectUpdateTest extends DuskTestCase
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
     * @group project
     * @group project.update
     * @return void
     */
    public function test_update_project_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid update project test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('name', 'Project Testing')
                        ->type('code', 'test')
                        ->type('class', 'TestService')
                        ->type('url', 'https://google.com')
                        ->type('key', 'akk82kkah910wj917ha188u217h')
                        ->click('@submit-update-project');
                })
                ->waitForText('Perubahan data project telah disimpan')
                ->assertSee('Perubahan data project telah disimpan');
        });
    }

    /**
     * @group project
     * @group project.update
     * @return void
     */
    public function test_update_project_with_empty_form()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid update project test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('name', '')
                        ->type('code', '')
                        ->type('class', '')
                        ->type('url', '')
                        ->type('key', '')
                        ->click('@submit-update-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid')
                ->assertSee('The name field is required.')
                ->assertSee('The code field is required.')
                ->assertSee('The class field is required.')
                ->assertSee('The url field is required.')
                ->assertSee('The key field is required.');
        });
    }

    /**
     * @group project
     * @group project.update
     * @return void
     */
    public function test_invalid_edit_name_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique name validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('name', 'Unioleo')->click('@submit-update-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The name has already been taken.'); // Check validation error message for unique name
        });
    }

    /**
     * @group project
     * @group project.update
     * @return void
     */
    public function test_invalid_edit_code_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique code validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('code', 'tamg')->click('@submit-update-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The code has already been taken.'); // Check validation error message for unique code

            // Run max length code validation test
            $browser->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('code', 'kodepanjang')->click('@submit-update-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The code must not be greater than 4 characters.'); // Check validation error message for max length code
        });
    }

    /**
     * @group project
     * @group project.update
     * @return void
     */
    public function test_invalid_edit_class_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique class validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('class', 'UoiService')->click('@submit-update-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The class has already been taken.'); // Check validation error message for unique class
        });
    }

    /**
     * @group project
     * @group project.update
     * @return void
     */
    public function test_invalid_edit_url_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique url validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('url', 'https://amg.eprocurement.id/api/public') // Fill url with already been taken url
                        ->click('@submit-update-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The url has already been taken.'); // Check validation error message for unique url

            // Run required format url validation test
            $browser->visitRoute('project')
                ->waitFor('#btn-edit-1')
                ->click('#btn-edit-1')
                ->waitFor('#edit-project-modal.show')
                ->whenAvailable('#edit-project-modal.show', function ($modal) {
                    $modal->type('url', 'bukanurl')->click('@submit-update-project'); // Fill url with non url string
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The url must be a valid URL.'); // Check validation error message for required format url
        });
    }
}
