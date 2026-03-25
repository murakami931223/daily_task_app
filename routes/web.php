<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Models\User;


//新規登録画面表示
Route::get('/create', [UserController::class, 'showCreate'])->name('users.create');

Route::post('/create', [UserController::class, 'store'])->name('store');

//もしユーザー登録されていなければ登録画面に遷移する処理
Route::middleware(['check.user'])->group(function () {
    //タスクのトップ画面表示
    Route::get('/tasks/{date?}', [TaskController::class, 'showIndex'])->name('tasks.index');
    //タスク登録
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    //タスク編集
    Route::put('/tasks', [TaskController::class, 'update'])->name('tasks.update');
    //タスク完了・完了解除
    Route::post('/tasks/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.change');
    
    //日課タスク登録画面表示
    Route::get('/master_tasks', [TaskController::class, 'showMasterTask'])->name('tasks.show_master');
    //日課タスク登録
    Route::post('/master_tasks', [TaskController::class, 'masterTaskStore'])->name('tasks.master_task.store');
    //日課タスク削除
    Route::delete('/master_tasks/{id}', [TaskController::class, 'masterTaskDelete'])->name('tasks.master_task.delete');
});

//トップ画面の条件分岐（初めてのユーザーなら新規登録画面に遷移させる）
Route::get('/', function (Request $request) {
    //クッキーからdevice_tokenを取得
    $token = $request->cookie('device_token');

    //トークンが存在し、かつデータベースにそのユーザーが登録されているかチェック
    if ($token && User::where('device_token', $token)->exists()) {
        return redirect()->route('tasks.index');
    } else {
        return redirect()->route('users.create');
    }

    })->name('top');