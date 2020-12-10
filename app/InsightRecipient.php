<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class InsightRecipient extends Model
{
    protected $table = 'insight_recipients';
    protected $fillable = ['group_id', 'email'];



    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            // Added table name to avoid ambiguities in belongsTo
            if (Auth::check())
                $builder->where('insight_recipients.group_id', '=', Auth::user()->current_group);
        });
    }
}
