<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function React\Promise\all;

class ArticleController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        auth()->loginUsingId(4);

        $articles = Article::latest()->paginate(20);
        return view('Admin.articles.all', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
//        User::create([
//            'level' => 'admin',
//            'name' => 'Alireza',
//            'email' => 'Alireza@gmail.com',
//            'password' => bcrypt('123456')]);
//
//        Auth::loginUsingId(1);
//        auth()->loginUsingId(1);

        $imageUrl = $this->uploadImages($request->file('images'));
        auth()->user()->article()->create(array_merge($request->all(), ['images' => $imageUrl]));
        return redirect(route('articles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        return view('Admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $file = $request->file('images');
        $inputs = $request->all();

        if ($file) {
            $inputs['images'] = $this->uploadImages($request->file('images'));
        } else {
            $inputs['images'] = $article->images;
            $inputs['images']['thumb'] = $inputs['imagesThumb'];

        }

        unset($inputs['imagesThumb']);
        $article->update($inputs);

        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect(route('articles.index'));
    }


}
