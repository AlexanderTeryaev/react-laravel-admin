<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'features', 'price', 'users_limit', 'plan_id'];

    protected $casts = [
        'features' => 'array'
    ];
}
