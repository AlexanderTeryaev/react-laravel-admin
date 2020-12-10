<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use App\Author;
use App\Http\Requests\Web\QuestionRequest;
use Illuminate\Http\Request;
use App\Quizz;
use App\Question;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view question')->only('index', 'data', 'show');
        $this->middleware('permission:create question')->only('create', 'store');
        $this->middleware('permission:edit question')->only('edit', 'update');
        $this->middleware('permission:delete question')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->rowsPerPage;
        $offset = $request->currentPage;
        $searchText = $request->searchText;
        $group = $request->group;
        $tempQuery = Question::withoutGlobalScope('admin_current_group')->where('question', 'LIKE', "%{$searchText}%");
        if ($group !=0) {
            $tempQuery = $tempQuery->where('group_id', '=' , $group);
        }
        $total_count = $tempQuery->get()->count();
        $questions = $tempQuery->offset($offset*$limit)->take($limit)
        ->with([
            'quizz' => function($query)
                        { 
                            $query->select('author_id','id','name'); 
                        },
            'quizz.author' => function($query)
                                { 
                                    $query->select('id', 'name'); 
                                }
        ])->get();
               
        for($i=0; $i<$questions->count(); $i++) {
            $question = $questions[$i];
            $question->createdAt = $question->created_at->diffForHumans();
            $question->more = \Illuminate\Support\Str::words($question->more, 7, '...');
            $question->status = $question->status ? 'Enabled' : 'Disabled';
        }
        return response()->json([
            "data" =>compact('questions', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quizzes = Quizz::withoutGlobalScope('status')->select('name', 'id')->get();
        $authors = Author::select('name', 'id')->get();
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        return response()->json([
            "data" =>compact( 'quizzes', 'authors', 'difficulty_array')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $question = (new \App\Question)->createAnswer($request);
        $question->storePicture($request);
        return response()->json([
            "data" =>$question
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::findOrFail($id);
        return response()->json([
            "data" =>$question
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::withoutGlobalScope('status')->findOrFail($id);
        $quizzes = Quizz::withoutGlobalScope('status')->select('name', 'id')->get();
        $authors = Author::select('name', 'id')->get();
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
            "data" =>compact('question', 'quizzes', 'authors', 'difficulty_array', 'difficulty_array_index')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, $id)
    {
        $question = Question::withoutGlobalScope('status')->findOrFail($id);
        $question->updateAnswer($request);
        if($request->hasFile('bg_url')){
            $question->storePicture($request);
        }
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $question = Question::withoutGlobalScope('status')->findOrFail($id);
        $question->softDeletes();
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::withoutGlobalScope('status')->findOrFail($id);
        $question->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
