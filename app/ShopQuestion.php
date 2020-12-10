<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class ShopQuestion extends Model
{
    use Searchable;

    protected $dates = ['deleted_at'];

    protected $table = "shop_questions";

    protected $fillable = ['question', 'good_answer', 'bad_answer', 'bg_url',
        'difficulty', 'shop_training_id', 'shop_quizz_id', 'shop_author_id', 'more'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'quizz_id' => $this->shop_quizz_id,
            'author_id' => $this->shop_author_id,
            'quizz' => $this->quizz()->first()->name,
            'question' => $this->question,
            'good_answer' => $this->good_answer,
            'bad_answer' => $this->bad_answer,
            'author' => $this->author->name,
            'more' => $this->more
        ];
    }

    /**
     * Get the group associated with the question.
     */
    public function training()
    {
        return $this->belongsTo(ShopTraining::class, 'shop_training_id');
    }

    /**
     * Get the quizz associated with the question.
     */
    public function quizz()
    {
        return $this->belongsTo(ShopQuizz::class, 'shop_quizz_id');
    }

    /**
     * Get the author associated with the question.
     */
    public function author()
    {
        return $this->belongsTo(ShopAuthor::class, 'shop_author_id');
    }

    public function storePicture($request)
    {
        if ($request->hasFile('bg_url')) {
            $file = $request->file('bg_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'questions/qu_bg_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_' . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update(['bg_url' => $imagename]);
        }
    }
}
