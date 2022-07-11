<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())->assertSee('Login');
    }

    public function loginForm(Browser $browser ,$email , $password){
$browser->type('email' , $email)
    ->type('password' , $password)
    ->check('remember')
    ->press('Login')
    ->assertPathIs('/home');
    }


    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@email' => 'input[name=email]',
            '@password' => 'input[name=password]',
            '@remember' => 'input[name=remember]',
        ];
    }
}
