<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ArticleController extends Controller
{


    public function single(Article $article)
    {


        $article->increment('viewCount');
//        Redis::incr("views.{$article->id}.articles");
        $comments = $article->comments()->where('approved', 1)->latest()->where('parent_id', 0)->with('comments')->latest()->get();
        return view('Home.article', compact('article', 'comments'));
    }
}
