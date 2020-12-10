<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserAnswer extends Model
{
    protected $table = 'user_answers';

    protected $fillable = ['result', 'ip', 'user_id', 'group_id', 'question_id', 'answered_at', 'is_enduro'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('user_answers.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('user_answers.group_id', '=', $user->current_group);
            }
        });
    }

    /**
     * Get answer question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Add a limit constrained upon the query.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  mixed  $value
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function getEnduroAnswers($builder, bool $value)
    {
        return $builder->where('is_enduro', '=', $value);
    }


    //
    //
    // From here all method should be useless after graphql usage
    //
    //

    public function fromGroupId($id)
    {
        return $this->select()->where('group_id', $id);
    }
}
