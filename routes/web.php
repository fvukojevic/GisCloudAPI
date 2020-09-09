<?php

use Illuminate\Support\Facades\Route;
use App\Constants\RouteConstants;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\PostsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/ping', function () {
    return 'pong';
});

Route::get('/users', [UsersController::class, 'index'])->name(RouteConstants::ROUTE_GET_USERS);
Route::get('/users/{id}', [UsersController::class, 'detail'])->name(RouteConstants::ROUTE_GET_SPECIFIC_USER);
Route::get('/users/{id}/posts', [UsersController::class, 'userPosts'])->name(RouteConstants::ROUTE_GET_USERS_POSTS);

Route::get('/posts', [PostsController::class, 'index'])->name(RouteConstants::ROUTE_GET_POSTS);
Route::get('/posts/{id}', [PostsController::class, 'detail'])->name(RouteConstants::ROUTE_GET_SPECIFIC_POST);
