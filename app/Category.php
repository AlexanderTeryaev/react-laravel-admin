<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Category extends Model
{
    use Searchable;

    protected $table = 'categories';

    protected $fillable = [
        'group_id',
        'name',
        'logo_url',
        'is_published'
    ];

    protected $attributes = [
        'is_published' => true
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('categories.group_id', '=', Auth::user()->current_group);
        });

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('categories.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('categories.group_id', '=', $user->current_group);
            }
        });
    }

    /**
     * Scope a query to only published categories.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        // Todo: Rework this before enable login in app
        if (!Auth::check())
            return $query->where('is_published', true);
        return $query;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'name' => $this->name,
            'is_published' => $this->is_published,
            'quizzes' => $this->quizzes()->pluck('name')->toArray()
        ];
    }

    /**
     * Get the group for the category.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the quizzes for the category.
     */
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quizz::class, 'category_quizzes');
    }

    /**
     * Number of quizzes of the category to which the user is subscribed
     *
     * @return Int
     */
    public function subQuizzesCount(): int
    {
        $user = Request::get('user');
        $user_quizzes = $user->quizzes()->pluck('quizzes.id');
        return $this->quizzes()->whereIn('quizzes.id', $user_quizzes)->count();
    }

    /**
     * Update category logo
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param \App\User $user
     * @return Boolean
     */
    public function updateLogo($image, $user): bool
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'categories/ct_logo_' . $user->id . '_' . $user->current_group_portal . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['logo_url' => $image_name]);
    }



    //
    //
    // From here all method should be useless after graphql usage
    //
    //
    public function subscribeAllQuizzes($items, $user)
    {
        foreach ($items as $id) {
            $quizzes = Quizz::select('quizzes.id as quizz_id')->join('category_quizzes', 'category_quizzes.quizz_id', '=', 'quizzes.id')
                ->where('category_quizzes.category_id', $id)
                ->where('quizzes.group_id', $user->current_group)
                ->get();
            foreach ($quizzes as $quiz) {
                $thequiz = Quizz::findOrFail($quiz->quizz_id);
                $thequiz->subscribe($user);
            }
        }
        return ["OK"];
    }

    public function fromGroupId($id)
    {
        return $this->select('id', 'group_id', 'name', 'logo_url')
            ->where('group_id',$id);
    }

    public function storePicture($request)
    {
        if ($request->hasFile('logo_url'))
        {
            $file = $request->file('logo_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'categories/ct_logo_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update([
                'logo_url' => $imagename,
            ]);
        }
    }

    public function softDeletes()
    {
        DB::table('categories_quizzes')->where('category_id', $this->id)->delete();
        $this->delete();
    }
}
