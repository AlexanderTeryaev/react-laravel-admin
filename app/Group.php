<?php

namespace App;

use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Nuwave\Lighthouse\Schema\Types\Scalars\Upload;

class Group extends Model
{
    use Billable;

    protected $table = 'groups';

    protected $dates = ['trial_ends_at'];

    protected $fillable = [
        'name',
        'logo_url',
        'status', // Todo: rename this field to suspended
        'description',
        'coins',
        'users_limit',
        'trial_ends_at'
    ];

    /**
     * Global scope a query to only enabled public groups.
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', '=', true);
        });
    }

    public function taxPercentage()
    {
        return AppHelper::instance()->getConfig('tax.percentage', 20);
    }

    /**
     * Get the answers of the group.
     */
    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Get the email domain allowed for the group.
     */
    public function allowed_domains()
    {
        return $this->hasMany(GroupAllowedDomain::class);
    }

    /**
     * Get invitations
     */
    public function invitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

    /**
     * Get the users for the group.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }

    /**
     * Get the users for the group.
     */
    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_managers');
    }

    /**
     * Get the questions for the group.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the quizzes for the group.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quizz::class);
    }

    /**
     * Get the categories for the group.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the authors for the group.
     */
    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    /**
     * Get the configs for the group.
     */
    public function configs(): HasMany
    {
        return $this->hasMany(GroupConfig::class);
    }

    /**
     * Get specific config of the group.
     * /!\ Used only for graphQL for the | Group -> config(key) | query
     */
    public function config(): HasOne
    {
        return $this->hasOne(GroupConfig::class);
    }

    /**
     * Get the purchases of the group.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(GroupPurchase::class);
    }

    /**
     * Get the populations of the group.
     */
    public function populations()
    {
        return $this->hasMany(GroupPopulation::class);
    }

    /**
     * Know if the group is the user's current_group
     *
     * @return Boolean
     */
    public function viewerIsCurrent(): bool
    {
        $user = Request::get('user');
        return $user->current_group == $this->id;
    }

    /**
     * Check if the group is the user's current_group_portal
     *
     * @return Boolean
     */
    public function viewerIsManaging(): bool
    {
        $user = Request::get('user');
        return $user->current_group_portal == $this->id;
    }

    /**
     * Update group logo
     *
     * @param Upload $image
     * @param User $user
     * @return Boolean
     */
    public function updateImage($image, $user)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'groups/gr_ico_' . $user->id . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['logo_url' => $image_name]);
    }

    /**
     * Check if the group is ready to receive users
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->onTrial() || $this->subscribed('main');
    }

    /**
     * Verify that the group has enough places available via users_limit field
     *
     * @return bool
     */
    public function isFull(): bool
    {
        if ($this->users_limit == 0) // 0 means unlimited
            return false;
        if ($this->users()->count() >= $this->users_limit)
            return true;
        return false;
    }


    /**
     * Copy training author in group avoiding duplicates
     *
     * @param Author $author
     * @return Author
     */
    private function copyTrainingAuthor($author)
    {
        $group_author = $this->authors()->where('name', '=', $author->name);
        if ($group_author->exists())
            return $group_author->first();
        return $this->authors()->create($author->toArray());
    }

    /**
     * Copy training quizzes in group
     *
     * @param ShopTraining $training
     * @param int $author_id
     * @return Quizz[]
     */
    private function copyTrainingQuizzes($training, $author_id)
    {
        $copied_quizzes = collect();
        // Import quizzes
        foreach ($training->quizzes as $quizz)
        {
            $newQuizz = $this->quizzes()->create([
                'name' => $quizz->name,
                'description' => $quizz->description,
                'image_url' => $quizz->image_url,
                'author_id' => $author_id,
                'tags' => $quizz->tags,
                'difficulty' => $quizz->difficulty,
                'enduro_limit' => 10,
                'is_geolocalized' => false,
                'status' => true
            ]);
            // Import quizz questions
            foreach ($quizz->questions as $question)
            {
                $newQuizz->questions()->create([
                    'group_id' => $this->id,
                    'author_id' => $newQuizz->author_id,
                    'question' => $question->question,
                    'bg_url' => $question->bg_url,
                    'good_answer' => $question->good_answer,
                    'bad_answer' => $question->bad_answer,
                    'difficulty' => $question->difficulty,
                    'more' => $question->more,
                    'status' => true
                ]);
            }
            $copied_quizzes->push($newQuizz);
        }
        return $copied_quizzes;
    }

    /**
     * Purchase a training
     *
     * @param ShopTraining $training
     * @return mixed
     */
    public function purchase($training)
    {
        $author = $this->copyTrainingAuthor($training->author);

        $category = $this->categories()->create([
            'name' => $training->name,
            'logo_url' => $training->author->pic_url,
        ]);

        $quizzes = $this->copyTrainingQuizzes($training, $author->id);
        $category->quizzes()->sync($quizzes->pluck('id'));

        $this->decrement('coins', $training->price);

        return $this->purchases()->create([
            'shop_training_id' => $training->id,
            'category_id' => $category->id,
            'price' => $training->price
        ]);
    }
}
