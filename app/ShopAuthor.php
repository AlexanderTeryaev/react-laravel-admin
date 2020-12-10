<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class ShopAuthor extends Model
{
    use Searchable;

    protected $dates = ['deleted_at'];

    protected $table = 'shop_authors';

    protected $fillable = ['pic_url', 'name', 'function', 'description', 'fb_link', 'twitter_link', 'website_link'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'function' => $this->function,
            'description' => $this->description,
            'quizzes' => $this->quizzes()->pluck('name')->toArray()
        ];
    }

    /**
     * Get the author quizzes
     */
    public function quizzes()
    {
        return $this->hasMany(ShopQuizz::class, 'id');
    }

    /**
     * Get the author questions
     */
    public function questions()
    {
        return $this->hasMany(ShopQuestion::class);
    }

    public function storePicture($request)
    {
        if ($request->hasFile('pic_url'))
        {
            $file = $request->file('pic_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'authors/au_ico_' . \Auth::user()->id . '_' . \Auth::user()->current_group . '_' . $this->id . '_' . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $this->update(['pic_url' => $imagename]);
        }
    }
}
