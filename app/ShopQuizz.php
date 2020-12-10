<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class ShopQuizz extends Model
{
    use Searchable;

    protected $dates = ['deleted_at'];
    //
    protected $table = 'shop_quizzes';

    protected $fillable = [
        'shop_training_id',
        'shop_author_id',
        'name',
        'tags',
        'image_url',
        'description',
        'difficulty',
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
            'tags' => $this->tags,
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
     * Get the training associated with the quizz
     */
    public function training() {
        return $this->belongsTo(ShopTraining::class, 'shop_training_id');
    }

    /**
     * Get the questions for the quizz.
     */
    public function questions()
    {
        return $this->hasMany(ShopQuestion::class, 'shop_quizz_id');
    }

    /**
     * Questions count to a quizz
     *
     * @return Int
     */
    public function questionsCount()
    {
        return $this->questions()->count();
    }

    public function storePicture($request)
    {
        if ($request->hasFile('image_url'))
        {
            $file = $request->file('image_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'quizzes/qz_bg_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update(['image_url' => $imagename]);
        }
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

}
