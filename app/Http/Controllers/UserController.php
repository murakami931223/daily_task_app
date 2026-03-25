<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Requests\UserRequest;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showCreate() {
        return view('users.create');
    }

    public function store(UserRequest $request) {
        try {
            // トランザクション開始
            DB::beginTransaction();
            // 登録処理呼び出し
            $user = User::registerUser($request);
            DB::commit();

            return redirect()->route('tasks.index')
                            ->with('success', '登録しました')
                            ->withCookie(cookie()->forever('device_token', $user->device_token));
                            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput();
        }
    }
}
