<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProjectCreateTest extends DuskTestCase
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
     * @group project.create
     * @return void
     */
    public function test_create_project_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid create project test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('name', 'Project Testing')
                        ->type('code', 'test')
                        ->type('class', 'TestService')
                        ->type('url', 'https://google.com')
                        ->type('key', 'akk82kkah910wj917ha188u217h')
                        ->click('@submit-create-project');
                })
                ->waitForText('Project baru telah dibuat')
                ->assertSee('Project baru telah dibuat'); // Check status text shown or not
        });
    }

    /**
     * @group project
     * @group project.create
     * @return void
     */
    public function test_create_project_with_blank_form()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run create project with blank form test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The name field is required.') // Check for name invalid message
                ->assertSee('The code field is required.') // Check for code invalid message
                ->assertSee('The class field is required.') // Check for class invalid message
                ->assertSee('The url field is required.') // Check for url invalid message
                ->assertSee('The key field is required.'); // Check for key invalid message
        });
    }

    /**
     * @group project
     * @group project.create
     * @return void
     */
    public function test_invalid_create_name_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique name validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('name', 'Kino Indonesia') // Fill name with already taken name
                        ->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The name has already been taken.'); // Check validation error message for unique name
        });
    }

    /**
     * @group project
     * @group project.create
     * @return void
     */
    public function test_invalid_create_code_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique code validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('code', 'kino') // Fill code with already taken code
                        ->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The code has already been taken.'); // Check validation error message for unique code

            // Run unique code validation test
            $browser->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('code', 'kodepanjang') // Fill code with string that has length > 4
                        ->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The code must not be greater than 4 characters.'); // Check validation error message for max length code
        });
    }

    /**
     * @group project
     * @group project.create
     * @return void
     */
    public function test_invalid_create_class_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique class validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('class', 'KinoService') // Fill class with already taken class
                        ->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The class has already been taken.'); // Check validation error message for unique class
        });
    }

    /**
     * @group project
     * @group project.create
     * @return void
     */
    public function test_invalid_create_url_validation()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run unique url validation test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('url', 'https://amg.eprocurement.id/api/public') // Fill url with already taken url
                        ->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The url has already been taken.'); // Check validation error message for unique url

            // Run formated url validation test
            $browser->visitRoute('project')
                ->click('@btn-create-project')
                ->waitFor('#add-project-modal.show')
                ->whenAvailable('#add-project-modal.show', function ($modal) {
                    $modal->type('url', 'bukanurl') // Fill url with no url format string
                        ->click('@submit-create-project');
                })
                ->waitFor('.is-invalid')
                ->assertPresent('.is-invalid') // Check if class is-invalid available
                ->assertSee('The url must be a valid URL.'); // Check validation error message for url format validation
        });
    }
}
