<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadArticleTest extends TestCase
{
    protected $article;

//    protected function setUp(): void
//    {
//        parent::setUp();
//        $this->article = Article::factory()->count(5)->create([
//            'user_id' => 1]);
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//        $this->article->delete();
//    }

    /**
     * @test
     */
    public function checkText()
    {

        $this->get('/home')
            ->assertSee('آخرین دوره ها');
    }



}
