<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class Featured extends Model
{
    protected $table = "featured";

    protected $fillable = ['name', 'pic_url', 'description', 'group_id', 'is_published', 'quizzes_add', 'quizzes_del', 'order_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('featured.group_id', '=', Auth::user()->current_group);
        });

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('featured.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('featured.group_id', '=', $user->current_group);
            }
        });
    }

    /**
     * Scope a query to only published featured.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', '=', true);
    }

    /**
     * Get the group associated with the featured.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * The quizzes that belong to the featured.
     */
    public function quizzes() {
        return $this->belongsToMany(Quizz::class, 'featured_quizzes');
    }

    /**
     * Update featured image
     *
     * @param \Nuwave\Lighthouse\Schema\Types\Scalars\Upload $image
     * @param \App\User $user
     * @return Boolean
     */
    public function updateImage($image, $user)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'featured/ft_pic_' . $user->id . '_' . $user->current_group_portal . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['pic_url' => $image_name]);
    }

    //
    //
    // From here all method should be useless after graphql usage
    //
    //

    public function fromGroupId($id)
    {
        return $this->select('featured.id as featured_id', 'featured.group_id as group_id', 'name', 'pic_url', 'description', 'order_id')
            ->where('featured.group_id', $id);
    }

    public function fromGroupAsc($id)
    {
        return $this->fromGroupId($id)
            ->orderBy('featured.order_id', 'ASC')
            ->get();
    }

    public function storePicture($request)
    {
        if ($request->hasFile('pic_url'))
        {
            $file = $request->file('pic_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'featured/ft_pic_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update([
                'pic_url' => $imagename,
            ]);
        }
    }
}
