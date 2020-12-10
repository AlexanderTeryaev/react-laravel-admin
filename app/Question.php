<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Question extends Model
{
    use Searchable;

    protected $dates = ['deleted_at'];

    protected $table = "questions";

    protected $fillable = [
        'group_id',
        'quizz_id',
        'question',
        'good_answer',
        'bad_answer',
        'bg_url',
        'difficulty',
        'more',
        'status'
    ];

    protected $attributes = [
        'status' => true
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('questions.group_id', '=', Auth::user()->current_group);
        });

        static::addGlobalScope('user_current_group', function (Builder $builder) {
            $user = Request::get('user');
            if ($user)
            {
                if (Auth::check() && Auth::payload()->get('is_portal'))
                    $builder->where('questions.group_id', '=', $user->current_group_portal);
                else
                    $builder->where('questions.group_id', '=', $user->current_group);
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
            'quizz_id' => $this->quizz_id,
            'quizz' => $this->quizz()->withoutGlobalScope('status')->first()->name,
            'question' => $this->question,
            'good_answer' => $this->good_answer,
            'bad_answer' => $this->bad_answer,
            'author' => $this->quizz->author->name,
            'more' => $this->more
        ];
    }

    /**
     * Get the group associated with the question.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the quizz associated with the question.
     */
    public function quizz()
    {
        return $this->belongsTo(Quizz::class);
    }

    /**
     * Update question background image
     *
     * @param \Nuwave\Lighthouse\Schema\Types\Scalars\Upload $image
     * @param \App\User $user
     * @return Boolean
     */
    public function updateImage($image, $user)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $image_name = 'questions/qu_bg_' . $user->id . '_' . $user->current_group_portal . '_' . $this->id . '_'  . Carbon::now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($image), 'public');
        return $this->update(['bg_url' => $image_name]);
    }

    //
    //
    // From here all method should be useless after graphql usage
    //
    //
    public function answers($user)
    {
        return $this->select('questions.id as question_id', 'quizz_id', 'questions.bg_url as background_url', 'user_answers.group_id as group_id', 'question', 'good_answer', 'bad_answer', 'questions.bg_url as bg_url', 'questions.difficulty', 'quizzes.author_id as author_id', 'user_answers.answered_at as date','more', 'questions.updated_at as updated', 'user_answers.result')
            ->join('user_answers', 'user_answers.question_id', '=', 'questions.id')
            ->where('questions.group_id', $user->current_group)
            ->join('quizzes', 'questions.quizz_id', '=', 'quizzes.id')
            ->where('user_answers.user_id', $user->id)
            ->get();
    }

    public function fromQuizId($groupid, $quizid)
    {
        return  $this->fromGroupId($groupid)->where('quizz_id', $quizid);
    }

    public function fromGroupId($id)
    {
        return $this->select('questions.id as question_id', 'quizz_id', 'bg_url as background_url', 'questions.group_id', 'question', 'good_answer', 'bad_answer', 'questions.bg_url', 'questions.difficulty', 'quizzes.author_id as author_id', 'more', 'questions.updated_at as updated')
            ->join('quizzes', 'quizzes.id', '=', 'questions.quizz_id')
            ->where('questions.group_id', $id);
    }

    public function subscribedQuestions($user)
    {
        return $this->select('questions.id as question_id', 'questions.quizz_id', 'questions.bg_url as background_url', 'quizzes.group_id as group_id', 'question', 'good_answer', 'bad_answer', 'quizzes.author_id as author_id', 'more', 'questions.updated_at as updated', 'questions.status')
        ->join('quizzes', 'quizzes.id', '=', 'questions.quizz_id')
        ->join('user_subscriptions', 'user_subscriptions.quizz_id', '=', 'quizzes.id')
        ->where('quizzes.group_id',  $user->current_group)
        ->where('quizzes.is_published', true)
        ->where('user_subscriptions.user_id', $user->id)
        ->get();
    }

    public function randomQuestions($user)
    {
        return $this->select('questions.id as question_id', 'questions.quizz_id', 'questions.bg_url as background_url', 'quizzes.group_id as group_id', 'good_answer', 'bad_answer', 'questions.author_id as author_id', 'more', 'questions.updated_at as updated', 'questions.status')
            ->join('quizzes', 'quizzes.id', '=', 'questions.quizz_id')
            ->join('user_subscriptions', 'user_subscriptions.quizz_id', '=', 'quizzes.id')
            ->where('quizzes.group_id',  $user->current_group)
            ->where('quizzes.is_published', true)
            ->where('user_subscriptions.user_id', $user->id)
            ->orderBy(DB::raw('RAND()'))
            ->limit(50)
            ->get();
    }


    public function storeDefaultImg($request)
    {
        $quiz = Quizz::withoutGlobalScope('status')->findOrFail($request->input('quizz_id'));
        if (($this->bg_url == null || $this->bg_url == '' || $this->bg_url == 'None') && $quiz->default_img_url != null)
        {
            $this->update(['bg_url' => $quiz->default_img_url]);
        }
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
        else
            $this->storeDefaultImg($request);
    }

    public function createAnswer($request)
    {
        $difficulty = 'EASY';
        switch ( $request->input('difficulty'))
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
        return Question::create([
            'question' => $request->input('question'),
            'good_answer' => $request->input('good_answer'),
            'bad_answer' =>  $request->input('bad_answer'),
            'bg_url' => 'None',
            'difficulty' => $difficulty,
            'group_id' => \Auth::user()->current_group,
            'quizz_id' => $request->input('quizz_id'),
            'author_id' => $request->input('author_id'),
            'status' => true,
            'more' => $request->input('more'),
        ]);
    }

    public function softDeletes()
    {
        DB::table('question_reports')->where('question_id', $this->id)->delete();
        DB::table('user_answers')->where('question_id', $this->id)->delete();
        $this->delete();
    }

    public function updatePortalAnswer($request)
    {
        $this->update(
            [
                'question' => $request->input('question'),
                'good_answer' => $request->input('good_answer'),
                'bad_answer' =>  $request->input('bad_answer'),
                'difficulty' => $request->input('difficulty'),
                'quizz_id' => $request->input('quizz_id'),
                'more' => $request->input('more'),
            ]
        );
    }

    public function updateAnswer($request)
    {
        $difficulty = 'EASY';
        switch ( $request->input('difficulty'))
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
        $this->update(
            [
                'question' => $request->input('question'),
                'good_answer' => $request->input('good_answer'),
                'bad_answer' =>  $request->input('bad_answer'),
                'difficulty' => $difficulty,
                'quizz_id' => $request->input('quizz_id'),
                'author_id' => $request->input('author_id'),
                'status' => (($request->input('status') == '0') ||
                    ($request->input('status') == '1' && $this->status == true)) ? true : false,
                'more' => $request->input('more'),
            ]
        );
    }

}
