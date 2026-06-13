<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StampHistory;
use Illuminate\Support\Facades\DB;

class StampController extends Controller
{   
    //スタンプ獲得
    public function confirmStamp(Request $request) {
        //ログインユーザー取得
        $user = User::findByToken($request);

        //未読スタンプを取得
        $unreadStamps = StampHistory::where('user_id',$user->id)
                                ->where('is_read', false)
                                ->count();

        if ($unreadStamps > 0) {
            try {
                DB::beginTransaction();

                $user->userStat->increment('perfect_stamp_count', $unreadStamps);

                StampHistory::where('user_id', $user->id)
                            ->where('is_read', false)
                            ->update(['is_read' => true]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false], 500);
            }
        }

        return response()->json(['success' => true]);
        
    }
}
