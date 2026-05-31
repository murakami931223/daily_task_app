<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Task;
use App\Models\StampHistory;

class EvaluateStampCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stamp:evaluate'; // コマンド名（後でスケジュール登録に使う）

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '前日のタスク達成率を判定してスタンプを付与する'; // 説明文（なんでもOK）

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = today()->subDay(); // 昨日の日付

        $users = User::with(['tasks' => function($query) use ($targetDate) {
            $query->whereDate('task_date', $targetDate);
        }, 'userStat'])->get();

        foreach ($users as $user) {
            $tasks = $user->tasks;

            if ($tasks->isEmpty()) continue;

            //達成率取得
            $rate = Task::calAchievementRate($tasks);

            //達成率が100%の時の処理
            if ($rate === 100) {
                $user->userStat->increment('perfect_stamp_count');

                StampHistory::firstOrCreate([
                    'user_id'    => $user->id,
                    'stamp_date' => $targetDate,
                ], [
                    'is_read' => false,
                ]);
            }
        }

        $this->info('スタンプ判定完了: ' . $targetDate);
    }
}
