<?php

namespace App;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;


class Author extends Model
{
    use Searchable;

    protected $dates = ['deleted_at'];

    protected $table = 'authors';

    protected $fillable = ['pic_url', 'name', 'function', 'description', 'fb_link', 'twitter_link', 'website_link', 'group_id'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('authors.group_id', '=', Auth::user()->current_group);
        });

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('authors.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('authors.group_id', '=', $user->current_group);
            }
        });
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
            'function' => $this->function,
            'description' => $this->description,
            'quizzes' => $this->quizzes()->limit(15)->pluck('name')->toArray()
        ];
    }

    /**
     * Get author group.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the author quizzes
     */
    public function quizzes()
    {
        return $this->hasMany(Quizz::class);
    }

    /**
     * Update author picture
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param \App\User $user
     * @return Boolean
     */
    public function updatePicture($image, $user)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'authors/au_ico_' . $user->id . '_' . $user->current_group_portal . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
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
        return $this->select()->where('group_id', $id);
    }

    public function isInGroup($id)
    {
        if ($this->group_id == $id)
            return true;
        else
            throw new Exception('The author you asked is not in your current_group', 403);
    }

    public function softDeletes()
    {
        $question = Question::select()->where('author_id', $this->id);
        $question->delete();
        $quiz = Quizz::select()->where('author_id', $this->id);
        $quiz->delete();
        $this->delete();
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

    public function fromEmail($email)
    {
        return  DB::table('authors')
            ->select()
            ->where('email', $email);
    }
}
