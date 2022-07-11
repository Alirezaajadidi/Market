<?php

namespace Tests\Unit;

use App\Models\User;
use Psy\Util\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCheckTest extends TestCase
{
    protected $user;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\Models\User::factory()->make(['active' => 1]);
    }

    /**
     * @test
     */
    public function send_data_for_register()
    {
        $response = $this->post('/register', $this->user->toArray());
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /**
     * @test
     */
    public function a_user_can_visit_user_panel()
    {
        $response = $this->actingAs($this->user)->get('/user/panel');
        $response->assertSee('پنل کاربری');
    }

    /**
     * @test
     */
    public function a_user_can_visit_user_panel_history_page(){
        $response = $this->actingAs($this->user)->get('/user/panel/history');
        $response->assertSee('مقدار پرداخت');
    }


}
