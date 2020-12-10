<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $dates = ['deleted_at'];

    protected $table = 'configs';

    protected $fillable = ['key', 'value'];
}
