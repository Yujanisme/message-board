<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ManagerController;
use GuzzleHttp\Psr7\Message;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::prefix('message')->group(function () {
    //前端留言板
    Route::get('/', [MessageController::class, 'index'])->name('message.view');
    Route::get('/lists', [MessageController::class, 'lists']);
    Route::post('/create',[MessageController::class,'create'])->name('message.create');    
    
    //管理員頁面
    Route::get('/manager',[ManagerController::class,'managerView'])->name('manager.view');
    Route::get('/managerList',[ManagerController::class,'managerData']);
    Route::post('/addManager',[ManagerController::class,'addManager'])->name('manager.add');
    Route::post('/deleteManager',[ManagerController::class,'delete']);
    //後端管理頁面
    Route::get('/content', [MessageController::class,'contentView'])->name('content.view');
    Route::post('/select',[MessageController::class,'select'])->name('content.select');
    Route::post('/reply',[MessageController::class,'reply']);
    Route::post('/edit',[MessageController::class,'edit_reply']);
    Route::post('/delete',[MessageController::class,'delete']);
    //登入頁面
    Route::get('/loginForm',[ManagerController ::class,'loginForm']);
    Route::post('/login',[ManagerController::class,'login'])->name('login.submit');
    Route::get('/logout',[ManagerController ::class,'logout']);
    Route::get('/checkSession',[ManagerController::class,'checkSession']);
});