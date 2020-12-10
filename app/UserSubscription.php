<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;


class UserSubscription extends Model
{
    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id', 'group_id', 'quizz_id'
    ];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('user_subscriptions.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('user_subscriptions.group_id', '=', $user->current_group);
            }
        });
    }


    /**
     * Get quizz subscription.
     */
    public function quizz(){
        return $this->belongsTo(Quizz::class);
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

    public function unsubscribe($user, $quiz_id)
    {
        $usersubscription = $this->fromGroupId($user->current_group)
            ->where('user_id', $user->id)
            ->where('quizz_id', $quiz_id)
            ->get();
        if ($usersubscription->first() == null)
            throw new Exception('Triyng to unsubscribe to a quizz you are not subscribe', 401);
        $id = $usersubscription->first()->id;
        if ($usersubscription->first()->delete())
        {
            return ['id' => $id];
        }
    }
}
