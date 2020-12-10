<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserStatistics extends Model
{
    protected $table = 'user_statistics';

    protected $attributes = [
        'bad_answers' => 0,
        'good_answers' => 0,
        'app_rank' => 0,
        'unlocks' => 0,
        'score' => 0,
        'user_id' => 0,
    ];

    protected $fillable = ['user_id', 'group_id', 'bad_answers', 'good_answers', 'app_rank', 'unlocks', 'user_id', 'score'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('user_statistics.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('user_statistics.group_id', '=', $user->current_group);
            }
        });
    }

    public function goodAnswerRate(){
        $total = $this->good_answers + $this->bad_answers;
        if ($total == 0)
            return 0;
        return round($this->good_answers / ($total / 100));
    }

}
