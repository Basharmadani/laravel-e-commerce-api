<?php
use App\Http\Controllers\AdminNotification;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\postController;
use App\Http\Controllers\ProfileWorkerController;
use App\Http\Controllers\RaitingController;
use App\Http\Controllers\WorkerAuthController;
use App\Models\raiting;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/admin'
], function ($router) {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::post('/refresh', [AdminController::class, 'refresh']);
    Route::get('/user-profile', [AdminController::class, 'userProfile']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/worker'
], function ($router) {
    Route::post('/login', [WorkerAuthController::class, 'login']);
    Route::post('/register', [WorkerAuthController::class, 'register']);
    Route::post('/logout', [WorkerAuthController::class, 'logout']);
    Route::post('/refresh', [WorkerAuthController::class, 'refresh']);
    Route::get('/user-profile', [WorkerAuthController::class, 'userProfile']);
    Route::get('/profile', [ProfileWorkerController::class, 'showprofile'])->middleware('auth:worker');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth/client'
], function ($router) {
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/register', [ClientAuthController::class, 'register']);
    Route::post('/logout', [ClientAuthController::class, 'logout']);
    Route::post('/refresh', [ClientAuthController::class, 'refresh']);
    Route::get('/user-profile', [ClientAuthController::class, 'userProfile']);

});



Route::post('/post/create',[postController::class,'create_post'] );



Route::controller(AdminNotification::class)->prefix('admin/notification')->group(function(){
    Route::get('/all','index');
    Route::get('/unread','unread');
    Route::get('/markRead','Asread');
    Route::delete('/delete','deleteall');
    Route::delete('/delete/{id}','deleteOneNotification');
    Route::get('/markRead/{id}','ReadOneNotification');


});



Route::controller(postController::class)->prefix('posts')->group(function(){
Route::get('/all','index')->middleware('auth:worker');
Route::get('/pending','pendingpost')->middleware('auth:admin');

Route::post('/create','create_post')->middleware('auth:worker');
Route::get('/aproved','aproved');
Route::post('/approvePost/{id}','approvePost')->middleware('auth:admin');
Route::post('/rejectPost/{id}','rejectPost')->middleware('auth:admin');
Route::get('/filter','filter')->middleware('auth:worker');



});




Route::controller(OrderController::class)->prefix('order')->group(function(){
    Route::post('/add','addorder')->middleware('auth:client');
    Route::get('/show','showOrder')->middleware('auth:worker');
    Route::post('/status/{id}','updateStatus')->middleware('auth:worker');



});


Route::controller(RaitingController::class)->prefix('rate')->group(function(){
    Route::post('/add','store')->middleware('auth:client');
    Route::get('/rate/{id}', 'getAverageRating')->middleware('auth:client');



});



