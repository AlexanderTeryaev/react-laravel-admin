<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class ShopTraining extends Model
{
    use Searchable;

    protected $dates = ['deleted_at'];
    //
    protected $table = 'shop_trainings';

    protected $fillable = [
        'shop_author_id',
        'name',
        'subtitle',
        'tags',
        'difficulty',
        'image_url',
        'description',
        'price',
        'is_published',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'shop_author_id' => $this->shop_author_id,
            'name' => $this->name,
            'description' => $this->description,
            'author' => $this->author->name,
            'subtitle' => $this->subtitle,
            'price' => $this->price,
            'tags' => $this->tags
        ];
    }

    /**
     * Get the author associated with the quizz.
     */
    public function author()
    {
        return $this->belongsTo(ShopAuthor::class, 'shop_author_id');
    }

    /**
     * Get the quizzes of the training.
     */
    public function quizzes()
    {
        return $this->hasMany(ShopQuizz::class, 'shop_training_id');
    }

    /**
     * Get the questions of the training.
     */
    public function questions()
    {
        return $this->hasMany(ShopQuestion::class, 'shop_training_id');
    }

    /**
     * Get groups that bought the training
     */
    public function groups()
    {
        return $this->hasMany(GroupPurchase::class);
    }

    /**
     * Questions count of the training
     *
     * @return Boolean
     */
    public function questionsCount()
    {
        return $this->questions()->count();
    }

    /**
     * Sample questions of the training
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sampleQuestions()
    {
        return $this->questions()->limit(3)->get();
    }

    /**
     * Training is purchased
     *
     * @return Boolean
     */
    public function isPurchased()
    {
        $user = Request::get('user');
        return $this->groups()->where('group_id', $user->current_group_portal)->exists();
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

    public function createTraining($request) {
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
        return  ShopTraining::create([
            'name' => $request->input('name'),
            'subtitle' => $request->input('subtitle'),
            'description' => $request->input('description'),
            'shop_author_id' => $request->input('author_id'),
            'difficulty' => $difficulty,
            'image_url' => 'None',
            'price' => $request->input('price')
        ]);
    }

    public function updateTraining($request)
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
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'subtitle' => $request->input('subtitle'),
            'difficulty' => $difficulty,
            'shop_author_id' => $request->input('author_id'),
            'price' => $request->input('price')
        ]);
    }

    public function storePicture($request)
    {
        if ($request->hasFile('image_url'))
        {
            $file = $request->file('image_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'training/tr_pic_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update(['image_url' => $imagename]);
        }
    }
}
