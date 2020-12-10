<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Requests\Web\QuestionTrainingRequest;
use Illuminate\Http\Request;
use App\ShopAuthor;
use App\ShopQuestion;
use App\ShopQuizz;
use App\ShopTraining;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class ShopQuestionController extends Controller
{
    public static $PAGINATOR_NB = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $limit = $request->rowsPerPage;
        $offset = $request->currentPage;
        $searchText = $request->searchText;
        $questions = ShopQuestion::where('question', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = ShopQuestion::where('question', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$questions->count(); $i++) {
            $question = $questions[$i];
            $question->more = \Illuminate\Support\Str::words($question->more, 7, '...');
            $question->createdAt = $question->created_at->diffForHumans();
        }
        
        return response()->json([
            "data" =>compact('questions', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $quizzes = ShopQuizz::pluck('name', 'id');
        foreach ($quizzes as $key => $value) {
            $quizzes[$key] = "#" . $key . " " . $value;
        }
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        return response()->json([
            "data" =>compact( 'quizzes', 'difficulty_array')
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(QuestionTrainingRequest $request)
    {
        $quizz = ShopQuizz::findOrFail($request->input('quizz_id'));
        $question =  ShopQuestion::create([
            'question' => $request->input('question'),
            'good_answer' => $request->input('good_answer'),
            'bad_answer' =>  $request->input('bad_answer'),
            'bg_url' => 'None',
            'difficulty' => $request->input('difficulty') + 1,
            'shop_training_id' => $quizz->training->id,
            'shop_quizz_id' => $quizz->id,
            'shop_author_id' => $quizz->author->id,
            'more' => $request->input('more'),
        ]);
        $question->storePicture($request);
        return response()->json([
            "data" =>$question
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $question = ShopQuestion::findOrFail($id);
        $quizzes = ShopQuizz::pluck('name', 'id');
        foreach ($quizzes as $key => $value) {
            $quizzes[$key] = "#" . $key . " " . $value;
        }
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        $difficulty_array_index = 1;
        switch ($question->difficulty)
        {
            case 'EASY':
                $difficulty_array_index = 0;
                break;
            case 'MEDIUM':
                $difficulty_array_index = 1;
                break;
            case 'HARD':
                $difficulty_array_index = 2;
                break;
            default :
                break;
        }
        return response()->json([
            "data" =>compact('question', 'quizzes','difficulty_array', 'difficulty_array_index')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, QuestionTrainingRequest $request)
    {
        $question = ShopQuestion::findOrFail($id);
        $quizz = ShopQuizz::findOrFail($request->input('quizz_id'));
        $question->update([
            'question' => $request->input('question'),
            'good_answer' => $request->input('good_answer'),
            'bad_answer' =>  $request->input('bad_answer'),
            'difficulty' => $request->input('difficulty') + 1,
            'shop_training_id' => $quizz->training->id,
            'shop_quizz_id' => $quizz->id,
            'shop_author_id' => $quizz->author->id,
            'more' => $request->input('more'),
        ]);
        if($request->hasFile('bg_url')){
            $question->storePicture($request);
        }
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $question = ShopQuestion::findOrFail($id);
        $questions = ShopQuestion::all();
        $idx = $questions->search(function ($quest) use ($question) {
            return $quest->id == $question->id;
        });
        $question->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}