<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShopQuestionImport extends Model
{
    protected $table = 'shop_question_imports';

    protected $fillable = ['admin_id', 'question', 'good_answer', 'bad_answer', 'bg_url', 'difficulty',
        'shop_quizz_id', 'shop_author_id', 'shop_training_id','more', 'imported', 'shop_question_id'];

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
                    if (count($data) != 8)
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
                        $question['more'],
                        $question['difficulty']
                        ) = $data;

                    $csv_errors = Validator::make($question, [
                        'question' => 'bail|required|min:5|max:80',
                        'good' => 'required|min:1|max:35',
                        'bad' => 'required|min:1|max:35',
                        'quizz_id' => [
                            'required',
                            Rule::exists('shop_quizzes', 'id')
                        ],
                        'more' => 'string|nullable',
                        'author_id' => [
                            'required',
                            Rule::exists('shop_authors', 'id')
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

                    $sqi = ShopQuestionImport::create([
                        'shop_quizz_id' => $question['quizz_id'],
                        'shop_author_id' => $question['author_id'],
                        'shop_training_id' => 1,
                        'question' => $question['question'],
                        'good_answer' => $question['good'],
                        'bad_answer' => $question['bad'],
                        'bg_url' => ($question['bg_url']) ? $question['bg_url'] : $bg,
                        'more' => $question['more'],
                        'difficulty' => $question['difficulty'],
                        'imported' => false,
                        'admin_id' => Auth::user()->id
                    ]);
                    $sqi->shop_training_id = $sqi->quizz->shop_training_id;
                    $sqi->save();
                }
                $line++;
            }
        }
        fclose($handle);
        return redirect('shop_imports');
    }

    public function import()
    {
        $question = ShopQuestion::create([
            'question' => $this->question,
            'good_answer' => $this->good_answer,
            'bad_answer' => $this->bad_answer,
            'bg_url' => $this->bg_url,
            'difficulty' => $this->difficulty,
            'shop_quizz_id' => $this->shop_quizz_id,
            'shop_author_id' => $this->shop_author_id,
            'shop_training_id' => $this->shop_training_id,
            'more' => $this->more,
        ]);

        $this->imported = true;
        $this->shop_question_id = $question->id;
        $this->save();
    }

    public function quizz()
    {
        return $this->belongsTo(ShopQuizz::class, 'shop_quizz_id');
    }

    public function author()
    {
        return $this->belongsTo(ShopAuthor::class, 'shop_author_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function training()
    {
        return $this->belongsTo(ShopTraining::class, 'shop_training_id');
    }
}
