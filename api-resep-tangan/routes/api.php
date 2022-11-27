<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ContentsController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewsController;
use App\Http\Middleware\EnsureJWT;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// domain/api/auth
Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class
], function () {
    Route::get('/me', 'me'); // Token Required
    Route::post('/refresh', 'refresh')->name('refresh');
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/update', 'update')->name('update');
    Route::post('/email-checker', 'email_check');
    Route::get('/is-valid-token', 'token_validation');
});

// domain/api/recipes
Route::group([
    'prefix' => 'recipes',
    'controller' => RecipesController::class
], function () {
    // domain/api/recipes/views
    Route::group([
        'prefix' => 'views',
        'controller' => ViewsController::class
    ], function () {
        Route::get('/', 'views');
        Route::post('/', 'add_views')->middleware('auth:api');
    });

    // domain/api/recipes/contents
    Route::group([
        'prefix' => 'contents',
        'controller' => ContentsController::class
    ], function () {
        Route::post('/', 'add_contents');
        // Route::put('/', 'update_contents');
        Route::delete('/', 'delete_contents');
        Route::get('/{basename}', 'media');
    });

    // domain/api/recipes/rating
    Route::group([
        'prefix' => 'rating',
        'controller' => RatingController::class,
    ], function () {
        Route::get('/', 'rating');
        Route::post('/', 'add_rating');
        Route::put('/', 'update_rating');
    });

    // domain/api/recipes/comments
    Route::group([
        'prefix' => 'comments',
        'controller' => CommentsController::class
    ], function () {
        Route::get('/', 'comments');
        Route::post('/', 'add_comment');
        Route::delete('/', 'delete_comment');
    });

    // base
    Route::post('/add', 'add_recipes');
    // Route::put('/', 'update_recipes');
    Route::delete('/delete', 'delete_recipes');
    Route::get('/top', 'top')->name('top_recipes');
    Route::get('/all', 'all');
    Route::get('/{title}', 'search');
    Route::get('/{username}/{title}', 'recipes')->name('get_recipes');
});


// domain/api/user
Route::group([
    'prefix' => 'user',
    'controller' => UserController::class
], function () {
    Route::get('/top', 'top');
    Route::get('/{username}', 'user');
    Route::get('/{username}/followers', 'followers');
    Route::get('/{username}/following', 'following');

    // domain/api/user/following
    Route::group([
        'prefix' => 'following',
        'controller' => FollowsController::class
    ], function () {
        Route::post('/', 'add_following');
        Route::delete('/', 'delete_following');
    });
});
