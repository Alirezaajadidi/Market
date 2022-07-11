<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{


    /**
     * @test
     */
    public function login()
    {

        $user = User::find(1);
        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 123456789)
                ->press('Login')
                ->assertPathIs('/home');
        });
        $this->be(User::find(1));
        $this->browse(function (Browser $browser) {
            $browser->visit('/user/panel/history')->assertSee('history');
        });
    }
}
