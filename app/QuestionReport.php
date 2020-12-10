<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionReport extends Model
{
    use notifiable;

    protected $table = "question_reports";
    protected $fillable = ['user_id', 'group_id', 'question_id', 'report', 'admin_id', 'author_id', 'status', 'review'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('group_id', '=', Auth::user()->current_group);
        });
    }

    public function routeNotificationForSlack() {
        return env('SLACK_WEBHOOK_URL');
    }

    public function fromUserId($uid)
    {
        return $this->where('user_id', $uid);
    }

    public function fromQuestionId($qid)
    {
        return $this->where('question_id', $qid);
    }

    public function fromGroupId($gid)
    {
        return $this->where('group_id', $gid);
    }

    public function getReportInfo()
    {
        return $this->select('id', 'question_id', 'group_id', 'user_id', 'report', 'status', 'admin_id', 'review', 'created_at');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
