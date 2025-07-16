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

Route::get('/', [ManagerController::class, 'loginForm'])->name('login.view');
Route::post('/login', [ManagerController::class, 'login'])->name('login.submit');
Route::post('/logout', [ManagerController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('manager')->group(function () {
    //管理員頁面
    Route::get('/', [ManagerController::class, 'manager'])->name('manager.view');
    Route::get('/managerList',[ManagerController::class,'managerList'])->name('manager.list');
    Route::post('/addManager',[ManagerController::class,'addManager'])->name('manager.add');
    Route::post('/deleteManager/{id}',[ManagerController::class,'delete'])->name('manager.delete');
    Route::get('/updatePasswordView/{id}',[ManagerController::class,'updatePasswordView'])->name('manager.updatePasswordView');
    Route::post('/updatePassword/{id}',[ManagerController::class,'updatePassword'])->name('manager.updatePassword');
    
    //後端管理頁面
    Route::get('/content', [MessageController::class,'contentView'])->name('message.contentView');
    Route::post('/query',[MessageController::class,'query'])->name('message.query');
    Route::post('/reply',[MessageController::class,'reply'])->name('message.reply');
    Route::post('/deleteMessage/{id}',[MessageController::class,'delete'])->name('message.delete');
});

Route::prefix('message')->group(function () {
    //前台留言頁面
    Route::get('/', [MessageController::class,'index'])->name('message.view');
    Route::get('/messageList', [MessageController::class,'messageList'])->name('message.list');
    Route::post('/create', [MessageController::class,'create'])->name('message.create');
});