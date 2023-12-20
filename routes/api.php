<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::prefix('auth')->group(function (){
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
    });
});  


Route::controller(PostController::class)->group(function(){
    Route::prefix('post')->group(function (){

        Route::get('all', 'index')->middleware(['auth.role:admin']);
        Route::post('new', 'store');
        Route::put('update/{id}','update');
        Route::delete('delete/{id}','delete')->middleware(['auth.role:author,admin']);
    });
});


Route::controller(CommentController::class)->group(function(){
    Route::prefix('comment')->group(function (){
        Route::get('all/{post_id}','index');
        Route::post('new', 'store');
        Route::put('update/{id}','update');
        Route::delete('delete/{id}','delete')->middleware(['auth.role:author,admin']);
    });
});