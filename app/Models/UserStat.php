<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserStat extends Model
{
    protected $fillable = [
        'user_id',
        'current_stamp_gauge',
        'perfect_stamp_count',
        'reward_tickets',
        'edit_tickets',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
