<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionImport extends Model
{
    protected $table = 'question_imports';

    protected $fillable = ['admin_id', 'question', 'good_answer', 'bad_answer', 'bg_url', 'difficulty', 'status', 'group_id',
                            'quizz_id', 'author_id', 'more', 'imported', 'question_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin_current_group', function (Builder $builder) {
            if (Auth::check())
                $builder->where('group_id', '=', Auth::user()->current_group);
        });
    }

    public function store($request)
    {
        $file_path = $request->csv->path();
        $line = 0;
        $bg = "";

        if ($request->delimiter == ',' || $request->delimiter == ';')
            $delimiter = $request->delimiter;

        if ($request->hasFile('bg_url'))
        {
            $file = $request->file('bg_url');
            $ext = strtolower($file->getClientOriginalExtension());
            $bg = 'questions/qs_bg_' . Auth::user()->id . '_' . Auth::user()->current_group . '_import_' . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($bg, file_get_contents($file), 'public');
        }

        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if ($line != 0)
                {
                    if (count($data) != 9)
                        return redirect()->back()
                            ->withErrors('Incorrect number of columns, please use the template or check the delimiter', 'import')
                            ->with('error_line', $line);

                    $question = array();
                    list(
                        $question['question'],
                        $question['good'],
                        $question['bad'],
                        $question['bg_url'],
                        $question['quizz_id'],
                        $question['author_id'],
                        $question['status'],
                        $question['more'],
                        $question['difficulty']
                        ) = $data;

                    $csv_errors = Validator::make($question, [
                        'question' => 'bail|required|min:5|max:80',
                        'good' => 'required|min:1|max:35',
                        'bad' => 'required|min:1|max:35',
                        'quizz_id' => [
                            'required',
                            Rule::exists('quizzes', 'id')->where(function($query){
                                $query->where('group_id', Auth::user()->current_group);
                            })
                        ],
                        'status' => 'required|boolean',
                        'more' => 'string|nullable',
                        'author_id' => [
                            'required',
                            Rule::exists('authors', 'id')->where(function($query){
                                $query->where('group_id', Auth::user()->current_group);
                            })
                        ],
                        'difficulty' => 'required|in:EASY,MEDIUM,HARD'
                    ])->errors();

//                    // Check duplicate questions
//                    // TODO: Add grp check + good_answer & bad_answer check
//                    if (QuestionImport::select('id')->where('question', $question['question'])->count())
//                        $csv_errors->add('duplicate', "The question is already waiting for import");
//                    // TODO: Add grp check + good_answer & bad_answer check + imported
//                    if (Question::select('id')->where('question', $question['question'])->count())
//                        $csv_errors->add('duplicate', "The question is already exist in questions");


                    if ($csv_errors->any()) {
                        return redirect()->back()
                            ->withErrors($csv_errors, 'import')
                            ->with('error_line', $line);
                    }

                    QuestionImport::create([
                        'group_id' => Auth::user()->current_group,
                        'quizz_id' => $question['quizz_id'],
                        'author_id' => $question['author_id'],
                        'question' => $question['question'],
                        'good_answer' => $question['good'],
                        'bad_answer' => $question['bad'],
                        'bg_url' => ($question['bg_url']) ? $question['bg_url'] : $bg,
                        'more' => $question['more'],
                        'difficulty' => $question['difficulty'],
                        'status' => $question['status'],
                        'imported' => false,
                        'admin_id' => Auth::user()->id
                    ]);
                }
                $line++;
            }
        }
        fclose($handle);
        return redirect('imports');
    }

    public function import()
    {
        $question = Question::create([
            'question' => $this->question,
            'good_answer' => $this->good_answer,
            'bad_answer' => $this->bad_answer,
            'bg_url' => $this->bg_url,
            'difficulty' => $this->difficulty,
            'group_id' => $this->group_id,
            'quizz_id' => $this->quizz_id,
            'author_id' => $this->author_id,
            'status' => $this->status,
            'more' => $this->more,
        ]);
        $this->imported = true;
        $this->question_id = $question->id;
        $this->save();
        
    }

    public function quizz()
    {
        return $this->belongsTo(Quizz::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
