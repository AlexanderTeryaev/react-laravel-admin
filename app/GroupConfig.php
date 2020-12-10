<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupConfig extends Model
{
    protected $dates = ['deleted_at'];

    protected $table = 'group_configs';

    protected $fillable = ['key', 'value', 'group_id'];
}
