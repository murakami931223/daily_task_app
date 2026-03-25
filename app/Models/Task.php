<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DailyMasterTask;
use Carbon\Carbon;

class Task extends Model
{

    //優先度の名前をまとめて管理
    const PRIORITIES = [
        1 => '低',
        2 => '中',
        3 => '高',
    ];

    protected $fillable = 
    [
        'user_id',
        'master_id',
        'title',
        'task_date',
        'is_completed',
        'priority',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyMasterTask()
    {
        return $this->belongsTo(DailyMasterTask::class, 'master_id');
    }

    //達成率計算
    public static function calAchievementRate($tasks) {
        $total = $tasks->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $tasks->where('is_completed', 1)->count();

        return (int)floor(($completed / $total) * 100);
    }

    //日課タスクかどうか判定
    public function isFromMaster() : bool
    {
        return !is_null($this->master_id);
    }

    //タスク登録
    //1件のタスクを登録する（共通部品）
    public static function createTask($userId, $date, $title, $priority) {
        if (empty($title)) return null;

        return self::create([
            'user_id'   => $userId,
            'task_date' => $date,
            'title'     => $title,
            'priority'  => $priority,
        ]);
    }
    // 一括登録
    public static function registerTask($request, $user) {
        foreach ($request->titles as $index => $title) {
            if (!empty($title)) {
                self::createTask(
                    $user->id, 
                    $request->task_date, 
                    $title, 
                    $request->priorities[$index] ?? 2
                );
            }
        }

        return true;
    }

    //タスク編集
    public static function updateTask($id, $title, $priority) {
        $task = self::find($id);
        if (!$task) return null;

        $task->update([
            'title'    => $title,
            'priority' => $priority,
        ]);

        return true;
    }

    //日課タスクが目的の日のタスク一覧に登録されていないなら登録する
    public static function registerDailyTask($masters, $user, $targetDate) {
        foreach ($masters as $master) {
            // Carbonオブジェクトなら文字列に変換（'Y-m-d'形式）
            $dateString = $targetDate instanceof \Carbon\CarbonInterface 
                        ? $targetDate->toDateString() 
                        : $targetDate;

            self::create([
                    'user_id'      => $user->id,
                    'master_id'    => $master->id,
                    'task_date'    => $dateString,
                    'title'        => $master->title,
                    'is_completed' => 0,
                ]);
        }
        return true;
    }
}
