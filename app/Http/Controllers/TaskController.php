<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\DailyMasterTask;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    //トップ画面表示
    public function showIndex(Request $request, $date = null) {
        //指定が無ければ今日、あればその日を基準にする
        $targetDate = Carbon::parse($date ?? Carbon::today())->toImmutable();

        //前後の日付を取得する
        $prevDate = $targetDate->subDay()->toDateString(); //前の日
        $nextDate = $targetDate->addDay()->toDateString(); //次の日

        //表示用にフォーマット
        $displayDate = $targetDate->format('Y/m/d');

        //ログインユーザー取得
        $user = User::findByToken($request);

        //表示中の日のタスクを全て取得
        $tasks = Task::where('user_id', $user->id)
                        ->whereDate('task_date', $targetDate)
                        ->orderBy('priority', 'asc')
                        ->get();

        //登録している日課タスクを全て取得
        $masters = DailyMasterTask::where('user_id', $user->id) 
                             ->with(['user'])
                             ->get();

        //該当日のタスクの中で、日課由来のものが何件あるか数える
        $dailyTaskCount = $tasks->whereNotNull('master_id')->count();

        //達成率取得
        $rate = Task::calAchievementRate($tasks);

        //日課タスクがあるのに今日のタスクに登録されていなければ登録処理をする
        if ($dailyTaskCount === 0 && $masters->isNotEmpty()) {
            try {
            // トランザクション開始
            DB::beginTransaction();
            // 登録処理呼び出し
            $tasks = Task::registerDailyTask($masters, $user, $targetDate);
            DB::commit();

            $tasks = Task::where('user_id', $user->id)
                        ->whereDate('task_date', $targetDate)
                        ->orderBy('priority', 'asc')
                        ->get();
            
            $rate = Task::calAchievementRate($tasks);

            } catch (\Exception $e) {
                DB::rollback();
            }
        }

                        
        return view('tasks.index', compact('tasks', 'displayDate', 'prevDate', 'nextDate', 'rate'));
    }

    //チェックボックスへのチェックによる達成率変動
    public function toggleStatus(Request $request) {
        //ログインユーザー取得
        $user = User::findByToken($request);
        //チェックを入れたタスクのid取得
        $checkId = $request->check_id;
        //該当タスク取得
        $task = Task::where('user_id', $user->id)
                        ->findOrFail($checkId);

        //チェックの真偽判定し登録
        $task->is_completed = $request->is_completed;
        $task->save();

        //達成率を更新
        $tasks = Task::where('user_id', $user->id)
                        ->whereDate('task_date', $task->task_date)
                        ->orderBy('priority', 'asc')
                        ->get();

        $newRate = Task::calAchievementRate($tasks);

        //日課タスクかどうか判定（js側でのクラス名付けかえ条件分岐のため）
        $isMaster = $task->isFromMaster();

        return response()->json([
            'success' => true,
            'new_rate' => $newRate,
            'is_master' => $isMaster,
        ]);
    }

    //タスク登録
    public function store(Request $request) {

        $user = User::findByToken($request);

        try {
            // トランザクション開始
            DB::beginTransaction();
            // 登録処理呼び出し
            $tasks = Task::registerTask($request, $user);
            DB::commit();

            $formattedDate = str_replace('/', '-', $request->task_date);

            return redirect()->route('tasks.index', ['date' => $formattedDate])
                                ->with('success', '登録しました');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('tasks.index', ['date' => $targetDate])
                            ->with('error', '登録に失敗しました。');
        }
    }

    //タスク編集
    public function update(Request $request) {
        $user = User::findByToken($request);
        $targetDate = str_replace('/', '-', $request->task_date);

        //編集ボタンを押した段階で、画面に残されているIDのリストを取得
        $remainingIds = $request->input('task_ids', []);

    try {
            // トランザクション開始
            DB::beginTransaction();
            //画面から消されたタスクをDBから削除
            Task::where('user_id', $user->id)
                ->whereDate('task_date', $targetDate)
                ->whereNotIn('id', $remainingIds)
                ->delete();

            foreach ($request->titles as $index => $title) {
                if (empty($title)) continue;

                $taskId = $request->task_ids[$index] ?? null;
                $priority = $request->priorities[$index] ?? 2;

                if ($taskId) {
                    Task::updateTask($taskId, $title, $priority);
                } else {
                    //＋ボタンで新規追加した時の挙動
                    Task::createTask($user->id, $targetDate, $title, $priority);
                }
            }

            DB::commit();
            return redirect()->route('tasks.index', ['date' => $targetDate])
                            ->with('success', '更新しました');
        
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('tasks.index', ['date' => $targetDate])
                            ->with('error', '更新に失敗しました。');
        }
    }

    //以下日課タスク関連
    //日課タスク画面表示
    public function showMasterTask(Request $request) {

        $user = User::findByToken($request);
        $tasks = DailyMasterTask::where('user_id', $user->id) 
                             ->with(['user'])
                             ->get();
                        
        return view('tasks.master_tasks', compact('tasks'));
    }

    //日課タスクの登録
    public function masterTaskStore(Request $request) {

        $user = User::findByToken($request);

        try {
            // トランザクション開始
            DB::beginTransaction();
            // 登録処理呼び出し
            $tasks = DailyMasterTask::registerMasterTask($request, $user);
            DB::commit();

            return redirect()->route('tasks.show_master')
                                ->with('success', '登録しました');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput();
        }

    }

    //日課タスクの削除
    public function masterTaskDelete($id) {

        try {
            // トランザクション開始
            DB::beginTransaction();
            // 登録処理呼び出し
            $task = DailyMasterTask::deleteMasterTask($id);
            DB::commit();

            return response()->json(['ajax_success' => true,
                                    'message' => '削除しました']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                                    'ajax_success' => false,
                                    'message' => '削除に失敗しました'
                                ], 500);
        }
    }
}
