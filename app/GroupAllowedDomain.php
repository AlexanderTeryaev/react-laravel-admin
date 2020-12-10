<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class GroupAllowedDomain extends Model
{
    protected $table = 'group_allowed_domains';

    protected $fillable = [
        'group_id',
        'population_id',
        'domain'

    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('group_allowed_domains.group_id', '=', $user->current_group_portal);
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
     * Get the group associated with population.
     */
    public function population()
    {
        return $this->belongsTo(GroupPopulation::class);
    }

    /**
     * Scope for searching
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if ($search != null && $search != "")
            return $query->where('domain', 'LIKE', "%{$search}%");
        return $query;
    }

    /**
     * Scope for allowed domain by population
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int $population_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopulation($query, $population_id)
    {
        if ($population_id != null)
            return $query->where('population_id', '=', $population_id);
        return $query;
    }
}
