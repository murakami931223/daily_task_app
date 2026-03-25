<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class DailyMasterTask extends Model
{

    protected $fillable = 
    [
        'user_id',
        'title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'master_id');
    }

    //日課タスク登録
    public static function registerMasterTask($request, $user) {
        foreach ($request->titles as $title) {
            if (!empty($title)) {
                $master = self::create([
                    'user_id'   => $user->id,
                    'title'     => $title,
                ]);

                //明日の分だけ普通のタスクとしても登録
                Task::create([
                    'user_id'   => $user->id,
                    'master_id' => $master->id,
                    'title'     => $title,
                    'task_date' => Carbon::tomorrow()->toDateString(),
                    'priority'  => 2,
                ]);
            }
        }

        return true;
    }

    //日課タスク削除
    public static function deleteMasterTask($id) {
        $master = self::findOrFail($id);

        //紐づいている明日のタスクも探して削除
        Task::where('master_id', $id)
                ->whereDate('task_date', '>', now()->toDateString())
                ->delete();

        return $master->delete();
    }
}
