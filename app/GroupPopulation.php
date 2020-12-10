<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class GroupPopulation extends Model
{
    protected $table = 'group_populations';

    protected $fillable = [
        'name',
        'description',
        'master_key',
        'is_enabled'
    ];

    protected $attributes = [
        'is_enabled' => true
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('group_populations.group_id', '=', $user->current_group_portal);
            }
        });
    }

    /**
     * Get the group associated with population.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get users associated with population.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups', 'population_id');
    }

    /**
     * Get global success rate for population
     *
     * @return int
     */
    public function overallSuccessRate(): int
    {
        $user_ids = $this->users()->pluck('users.id');
        $total =  UserAnswer::whereIn('user_id', $user_ids)->count();
        $good_answers_count =  UserAnswer::whereIn('user_id', $user_ids)->where('result', true)->count();
        if ($total == 0)
            return 0;
        return round(($good_answers_count/$total) * 100);
    }
}
