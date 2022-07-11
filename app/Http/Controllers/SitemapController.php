<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap', 20);

        if (!$sitemap->isCached()) {
            $sitemap->addSitemap(URL::to('/sitemap-articles'));

        }
        return $sitemap->render('sitemapindex');
    }

    public function article()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap.articles', 60);

        if (!$sitemap->isCached()) {
            $articles = Article::latest()->get();
            foreach ($articles as $article) {
                $sitemap->add(URL::to($article->path()), $article->created_at, '1.0', 'daily');
            }
        }
        return $sitemap->render();
    }
}
