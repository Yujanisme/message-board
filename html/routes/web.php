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
    Route::get('/', [MessageController::class, 'index'])->name('message.view'); // 留言板
    Route::get('/lists', [MessageController::class, 'messageList'])->name('message.list'); // 全部留言列表
    Route::post('/create',[MessageController::class,'create'])->name('message.create'); // 新增留言
    
    //管理員頁面
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
    
    //登入頁面
    Route::get('/loginForm',[ManagerController ::class,'loginForm'])->name('login.view');
    Route::post('/login',[ManagerController::class,'login'])->name('login.submit');
    Route::get('/logout',[ManagerController ::class,'logout'])->name('logout');
    Route::get('/checkSession',[ManagerController::class,'checkSession']);
});