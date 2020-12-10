<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAuthentication extends Model
{
    protected $table = "email_authentications";
    protected $casts = ['metadata' => 'array'];

    protected $fillable = [
        'type',
        'email',
        'code',
        'ip',
        'metadata',
        'verified_at'
    ];

}
