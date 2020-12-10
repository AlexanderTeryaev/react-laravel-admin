<?php

namespace App;

use App\Http\Resources\App\UserSubscriptionResource;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Quizz extends Model
{
    use Searchable;

    protected $table = 'quizzes';
    protected $casts = ['tags' => 'array'];

    protected $attributes = [
        'enduro_limit' => 10,
        'is_published' => true
    ];

    protected $fillable = [
        'group_id',
        'author_id',
        'name',
        'tags',
        'image_url',
        'default_questions_image',
        'description',
        'difficulty',
        'enduro_limit',
        'is_geolocalized',
        'latitude',
        'longitude',
        'radius',
        'is_published',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('quizzes.group_id', '=', Auth::user()->current_group);
        });

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('quizzes.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('quizzes.group_id', '=', $user->current_group);
            }
        });
    }

    /**
     * Scope a query to only include popular quizzes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query, $args)
    {
        $limit = (isset($args['limit'])) ? $args['limit'] : 10;
        if ($limit > 50)
            $limit = 50;
          if ($args['weekly'])
               return $query->published()->withCount(['users' => function (Builder $query) {
                                    $query->where('user_subscriptions.created_at',  '>', now()->subDays(7));
                                }])->orderBy('users_count', 'DESC')
                                ->limit($limit);
            return $query->withCount('users')
                            ->orderBy('users_count', 'DESC')
                            ->limit($limit);
    }

    /**
     * Scope a query to only published quizzes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
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
            'author_id' => $this->author_id,
            'name' => $this->name,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
            'author' => $this->author->name,
            'tags' => $this->tags,
            'is_published' => $this->is_published,
            'categories_ids' => $this->categories()->pluck('categories.id')->toArray(),
            'categories' => $this->categories()->pluck('name')->toArray(),
            'featured' => $this->featured()->pluck('name')->toArray()
        ];
    }

    /**
     * The categories that belong to the quizz.
     */
    public function categories() {
        return $this->belongsToMany(Category::class, 'category_quizzes');
    }

    /**
     * The featured that belong to the quizz.
     */
    public function featured() {
        return $this->belongsToMany(Featured::class, 'featured_quizzes');
    }

    /**
     * Get the group associated with the quizz.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the author associated with the quizz.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * The users that belong to the quizz.
     */
    public function users() {
        return $this->belongsToMany(User::class, 'user_subscriptions');
    }

    /**
     * Get the questions for the quizz.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Know if a user is subscribed to a quizz
     *
     * @return Boolean
     */
    public function viewerIsSubscribed()
    {
        $user = Request::get('user');
        return $user->isSubscribed($this->id);
    }

    /**
     * Questions count to a quizz
     *
     * @return Boolean
     */
    public function questionsCount()
    {
        return $this->questions()->count();
    }

    /**
     * Get user enduro grades for this quizz
     *
     * @return Collection
     */
    public function userEnduroGrades()
    {
        $user = Request::get('user');
        $questions_ids = $this->questions()->pluck('id');

        $enduro_grades = $user->answers()
                                ->select('answered_at')
                                ->selectRaw('sum(result) as score')
                                ->selectRaw('count(*) as total')
                                ->where('is_enduro', true)
                                ->whereIn('question_id', $questions_ids)
                                ->groupBy('answered_at')
                                ->orderBy('answered_at', 'DESC')
                                ->get();
        foreach ($enduro_grades as $enduro_grade)
            $enduro_grade->answers =  $user->answers()
                ->where('is_enduro', true)
                ->where('answered_at', $enduro_grade->answered_at)
                ->get();

        return $enduro_grades;
    }

    /**
     * Get user progress for this quizz
     *
     * @return int
     */
    public function userProgressPercentage(): int
    {
        $user = Request::get('user');

        $questions_ids = $this->questions()->pluck('id');
        $good_answered_questions = $user->answers()
            ->distinct('question_id')
            ->where('result', true)
            ->whereIn('question_id', $questions_ids)
            ->count('question_id');

        if ($questions_ids->count() == 0)
            return 0;
        return round($good_answered_questions / $questions_ids->count() * 100);
    }

    /**
     * Get global success rate
     *
     * @return int
     */
    public function overallSuccessRate(): int
    {
        $questions_ids = $this->questions()->pluck('id');
        $total =  UserAnswer::whereIn('question_id', $questions_ids)->count();
        $good_answers_count =  UserAnswer::whereIn('question_id', $questions_ids)->where('result', true)->count();
        if ($total == 0)
            return 0;
        return round(($good_answers_count/$total) * 100);
    }

    /**
     * Update quizz image
     *
     * @param \Nuwave\Lighthouse\Schema\Types\Scalars\Upload $image
     * @param \App\User $user
     * @return Boolean
     */
    public function updateImage($image, $user)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'quizzes/qz_bg_' . $user->id . '_' . $user->current_group_portal . '_' . $this->id . '_'  . now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['image_url' => $image_name]);
    }

    /**
     * Update quizz default question image
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param \App\User $user
     * @return Boolean
     */
    public function updateDefaultImage($image, $user)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'questions/qu_default_' . $user->id . '_' . $user->current_group_portal . '_' . $this->id . '_'  . now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['default_questions_image' => $image_name]);
    }

    //
    //
    // From here all method should be useless after graphql usage
    //
    //

    public function createQuizz($request)
    {
        $difficulty = 'EASY';
        switch ($request->input('difficulty'))
        {
            case 0:
                $difficulty = 'EASY';
                break;
            case 1:
                $difficulty = 'MEDIUM';
                break;
            case 2:
                $difficulty = 'HARD';
                break;
            default :
                break;
        }
        return Quizz::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'group_id' => \Auth::user()->current_group,
            'difficulty' => $difficulty,
            'enduro_limit' => $request->input('enduroLimit'),
            'author_id' => $request->input('author_id'),
            'radius' => $request->input('radius'),
            'is_published' => true,
        ]);
    }

    public function updateQuizz($request)
    {
        $difficulty = 'EASY';
        switch ($request->input('difficulty'))
        {
            case 0:
                $difficulty = 'EASY';
                break;
            case 1:
                $difficulty = 'MEDIUM';
                break;
            case 2:
                $difficulty = 'HARD';
                break;
            default :
                break;
        }
        $this->update([
            'name' => $request->name,
            'description' => $request->input('description'),
            'is_published' => (($request->input('is_published') == '0') ||
                ($request->input('is_published') == '1' && $this->is_published == true)) ? true : false,
            'enduro_limit' => $request->input('enduroLimit'),
            'difficulty' => $difficulty,
            'author_id' => $request->input('author_id'),
            'radius' => $request->input('radius')
        ]);
    }

    public function authorWithoutGlobalScopes()
    {
        return $this->author()->withoutGlobalScopes();
    }

    public function getAuthorsQuizzes($id)
    {
        return $this->select('id as quizz_id')
        ->where('author_id', $this->id)
        ->where('group_id', $id)
        ->where('quizzes.is_geolocalized', '=', false)
        ->orderBy(DB::raw('RAND()'))
        ->limit(10)
        ->get();
    }

    public function categoriesWithGroupId($category_id, $group_id)
    {
        return $this->select('quizzes.id as quizz_id')
            ->join('category_quizzes', 'category_quizzes.quizz_id', '=', 'quizzes.id')
            ->where('category_quizzes.category_id', $category_id)
            ->where('quizzes.is_geolocalized', '=', false)
            ->where('quizzes.group_id',$group_id)
            ->get();
    }

    public function featuredWithGroupId($featured_id, $group_id)
    {
        return $this->select('quizzes.id as quizz_id')
            ->join('featured_quizzes', 'featured_quizzes.quizz_id', '=', 'quizzes.id')
            ->where('quizzes.is_geolocalized', '=', false)
            ->where('featured_quizzes.featured_id', $featured_id)
            ->where('quizzes.group_id', $group_id)
            ->get();
    }

    public function storeDefaultPicture($request)
    {
        if ($request->hasFile('default_questions_image'))
        {
            $file = $request->file('default_questions_image');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'questions/qu_default_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_'  . now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update(['default_questions_image' => $imagename,]);
        }
    }

    public function storePicture($request)
    {
        if ($request->hasFile('image_url'))
        {
            $file = $request->file('image_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'quizzes/qz_bg_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_'  . now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update(['image_url' => $imagename,]);
        }
    }

    public function softDeletes()
    {
        DB::table('category_quizzes')->where('quizz_id', $this->id)->delete();
        DB::table('featured_quizzes')->where('quizz_id', $this->id)->delete();
        $question = Question::select()->where('quizz_id', $this->id);
        $question->delete();
        $this->delete();
    }

    public function subscribe($user)
    {
        if (!UserSubscription::select()
            ->where('user_id', $user->id)
            ->where('group_id', $user->current_group)
            ->where('quizz_id', $this->id)->exists())
        {
            $usersubcription = UserSubscription::create([
                'group_id' => $user->current_group,
                'user_id' => $user->id,
                'quizz_id' => $this->id,
            ]);
            return new UserSubscriptionResource($usersubcription);
        }
        return ["OK"];
    }

    public function fromGroupId($id)
    {
        return $this->select('id as quizz_id', 'group_id', 'name', 'image_url', 'description', 'author_id', 'latitude', 'longitude', 'radius', 'default_questions_image', 'enduro_limit', 'tags')
            ->where('group_id', $id);
    }

    public function geolocalized($id)
    {
        return $this->fromGroupId($id)->where('is_geolocalized', '=', true);
    }

    public function fromGroupIdNoGeolocalized($id)
    {
        return  $this->fromGroupId($id)->where('is_geolocalized', '=', false);
    }

    public function fromGroupIdNoQuizzId($id)
    {
        return $this->select('id', 'group_id', 'name', 'image_url', 'description', 'author_id', 'default_quizzes_url', 'enduro_limit', 'tags')
            ->where('group_id', $id);
    }

    public function setTags($request)
    {
        $arraytags = [];
        if ($request->input('tags'))
        {
            $tags = $request->input('tags');
            $arraytags = explode(",", $tags);
        }
        $this->update(['tags' => $arraytags]);
    }

    public function popular($id, $nb)
    {
        return $this->published()->select('quizzes.id as quizz_id', DB::raw('count(*) as total'))
            ->join('user_subscriptions', 'user_subscriptions.quizz_id', '=', 'quizzes.id')
            ->where('user_subscriptions.group_id', $id)
            ->where('user_subscriptions.created_at', '>', now()->subDays(7))
            ->where('is_geolocalized', '=', false)
            ->groupBy('user_subscriptions.quizz_id')
            ->orderBy('total', 'Desc')
            ->limit($nb);
    }

    public function geolocation($request)
    {
        if ($request->input('degeolocalized') == 'on')
        {
            $this->update([
                'latitude' => null,
                'longitude' => null,
                'radius' => null,
                'is_geolocalized' => false,
            ]);
            return;
        }
        if ($request->input('latitude'))
        {
            $this->update([
                'latitude' => floatval($request->input('latitude')),
            ]);
        }
        if ($request->input('longitude'))
        {
            $this->update([
                'longitude' => floatval($request->input('longitude')),
            ]);
        }
        if ($this->latitude && $this->longitude && $this->radius)
        {
            if ($this->is_geolocalized == false) {
                $this->update([
                    'is_geolocalized' => true
                ]);
            }
        }
    }

    public function portalGeolocation($request)
    {
        $this->update([
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'radius' => $request->input('radius'),
        ]);

        if ($this->latitude && $this->longitude && $this->radius)
        {
            if ($this->is_geolocalized == false) {
                $this->update([
                    'is_geolocalized' => true
                ]);
            }
        }
        else
        {
            if ($this->is_geolocalized == true) {
                $this->update([
                    'is_geolocalized' => false
                ]);
            }
        }
    }
}
