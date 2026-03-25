<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\UserStat;
use App\Models\Task;
use App\Models\DailyMasterTask;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'device_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userStat()
    {
        // Userは1つのUserStatを持っている（1対1）
        return $this->hasOne(UserStat::class);
    }

    public function dailyMasterTask()
    {
        return $this->hasMany(DailyMasterTask::class);
    }

    public function task()
    {
        return $this->hasMany(Task::class);
    }

    //ログインユーザー取得のための専用メソッド
    public static function findByToken($request) {
        $token = $request->cookie('device_token');
        return self::where('device_token', $token)->first();
    }

    public static function registerUser($request) {
        //ユーザー作成
        $user = self::create([
            'name' => $request->name,
            'device_token' => Str::uuid(),
        ]);

        //初期ステータス作成
        $user->userStat()->create([
            'current_stamp_gauge' => 0,
            'perfect_stamp_count' => 0,
            'reward_tickets' => 0,
            'edit_tickets' => 3, //変更チケット初回登録プレゼント
        ]);

        return $user;
    }
}
