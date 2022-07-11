<?php

use App\Events\UserRegisterd;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\LevelManageController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\ArticleController;

use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserPanelController;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use UxWeb\SweetAlert\SweetAlert;

//for admin
//Route::resource('article')


Route::get('course', [\App\Http\Controllers\CourseController::class, 'index']);
Route::post('course/filter', [\App\Http\Controllers\CourseController::class, 'filtering']);


//Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/search', [\App\Http\Controllers\HomeController::class, 'search']);


//download route
Route::get('download/{id}', [\App\Http\Controllers\CourseController::class, 'download']);


Route::group(['prefix' => '/user/panel', 'middleware' => 'auth'], function () {
    Route::get('/', [UserPanelController::class, 'index'])->name('user.panel');
    Route::get('/history', [UserPanelController::class, 'history'])->name('user.panel.history');
    Route::get('/vip', [UserPanelController::class, 'vip'])->name('user.panel.vip');

    Route::post('/payment', [UserPanelController::class, 'vipPayment'])->name('user.panel.vip.payment');
    Route::get('/checker', [UserPanelController::class, 'vipChecker'])->name('user.panel.vip.checker');

});


Route::get('/sitemap', [\App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/sitemap-articles', [\App\Http\Controllers\SitemapController::class, 'article']);
//Route::get('feed/article', [\App\Http\Controllers\FeedController::class, 'articles']);


Route::get('/admin/comments/unsuccessful', [CommentController::class, 'unsuccessful']);
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('articles/{articleSlug}', [\App\Http\Controllers\ArticleController::class, 'single']);
Route::get('courses/{courseSlug}', [\App\Http\Controllers\CourseController::class, 'single']);
Route::post('/comment', [\App\Http\Controllers\HomeController::class, 'comment']);
Route::post('/comment/parent', [\App\Http\Controllers\HomeController::class, 'ParentComment']);

Route::get('alert', function () {
    SweetAlert::message('Message', 'Optional Title');
    return view('home');
});


Route::group(['middleware' => 'auth:web'], function () {
    Route::post('/course/payment', [\App\Http\Controllers\CourseController::class, 'payment']);
    Route::get('/course/payment/checker', [\App\Http\Controllers\CourseController::class, 'checker']);
});


Route::get('user/active/email/{token}', [\App\Http\Controllers\ActivationController::class, 'activation'])->name('activation.account');





//
Route::group(['middleware' => ['auth:web', 'checkAdmin'] ,'prefix' => 'admin'], function () {
//    $this->resource('articles' , 'ArticleController');
    Route::post('/panel/upload-image', [PanelController::class, 'uploadImageSubject']);

    Route::get('/panel', [PanelController::class, 'index']);
    Route::resource('/articles', ArticleController::class);
    Route::resource('/courses', CourseController::class);
    Route::resource('/episodes', EpisodeController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', permissionController::class);
    //comment section
    Route::resource('/comments', CommentController::class);
    Route::get('/comments/unsuccessful', [CommentController::class, 'unsuccessful']);

    //payment section
    Route::resource('/payments', PaymentController::class);
    Route::get('/payments/unsuccessful/page', [PaymentController::class, 'unsuccessful']);

    Route::prefix('users')->group(function () {

        Route::get('/', [UserController::class, 'index']);
        Route::resource('level', LevelManageController::class, ['parameters' => ['level' => 'user']]);

        Route::delete('{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    });

});


Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
//    $user = Socialite::driver('google')->stateless()->user();
//     $user->token;
//     return redirect('/');
    $social_user = Socialite::driver('google')->stateless()->user();
    $user = \App\Models\User::whereEmail($social_user->getEmail())->first();


    if (!$user) {
        $user = \App\Models\User::create([
            'name' => $social_user->getName(),
            'email' => $social_user->getEmail(),
            'password' => bcrypt($social_user->getId())
        ]);
    }

    if ($user->active == 0) {
        $user->update([
            'active' => 1
        ]);
    }

    auth()->loginUsingId($user->id);
    return redirect('/home');
});


//Route::group(['namespace' => 'Auth'], function () {
// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);
//});
Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/getData', function () {
    $validator = Validator::make(request()->all(), [
        'message' => 'required',
        'g-recaptcha-response' => 'recaptcha'
    ]);
    if ($validator->fails()) {
        return 'fails';
    } else {
        return request('message');

    }

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {

//    $user = User::find(7);
//    event(new \App\Events\ArticleEvent($user));
//    return 'done';

    return view('Home.master');
});
