<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function articles()
    {

        $articles = Article::latest()->get();
        return response(['data' => $articles, 'status' => 200], 200);
    }

    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['data' => $validator->errors()->all(), 'status' => 403], 403);
        }
    }
}
