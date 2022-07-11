<?php

namespace App\Http\Controllers;

use App\Events\UserActivation;
use App\Jobs\SendMail;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {


//        $job = new SendMail(User::find(1), 'regregregr');
////        $job->onQueue('sendMail');
//        dispatch($job);

//        event(new UserActivation(User::find(1)));

//        return auth()->loginUsingId(1);
//        cache()->pull('articles');
//        cache()->pull('courses');


//        cache(['vv' => 1], \Carbon\Carbon::now()->addMinute(10));
        cache()->flush();
//        return cache()->increment('vv');
//        return Cache::store('file')->get('name');

        if (cache()->has('articles')) {
            $articles = cache('articles');
        } else {
                $articles = Article::latest()->take(8)->get();
                cache(['articles' => $articles], Carbon::now()->addMinutes(10));
            }

        if (cache()->has('courses')) {
            $courses = cache('courses');
        } else {
            $courses = Course::latest()->take(4)->get();
            cache(['courses' => $courses], Carbon::now()->addMinutes(10));
        }

        return view('Home.index', compact('articles', 'courses'));
    }

    public function search(Request $request)
    {
        $articles = Article::orderby('id', 'ASC');

        if ($request->search) {
            $articles = $articles->where('title', 'LIKE', '%' . $request->search . '%')
                ->orWhere('tags', 'LIKE', '%' . $request->search . '%');

        }

        $articles = $articles->paginate(10)->appends('search', $request->search);
        return $articles;


    }

    public function comment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        Comment::create(array_merge([
            'user_id' => auth()->user()->id,
        ], $request->all()));
        return back();
    }

}
