<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Itpi\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProjectDeleteTest extends DuskTestCase
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
     * @group project.delete
     * @return void
     */
    public function test_delete_project_success()
    {
        // Find user
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {
            // Run valid update project test
            $browser->loginAs($user)
                ->visitRoute('project')
                ->waitFor('#btn-delete-1')
                ->click('#btn-delete-1') // Click delete button
                ->waitFor('.swal2-popup')
                ->whenAvailable('.swal2-popup', function ($modal) {
                    $modal->click('.swal2-confirm'); // Click alert confirm button
                })
                ->waitForText('Data project telah dihapus')
                ->assertSee('Data project telah dihapus'); // Check success message text
        });
    }
}
