<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;

use App\QuestionImport;
use App\Http\Requests\Web\ImportRequest;
use Illuminate\Http\Request;

class ImportController extends Controller
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
        $questions = QuestionImport::withoutGlobalScope('admin_current_group')->where(['question', 'LIKE', "%{$searchText}%"], ['imported', false])->offset($offset*$limit)->take($limit)
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
        $total_count = QuestionImport::withoutGlobalScope('admin_current_group')->where(['question', 'LIKE', "%{$searchText}%"], ['imported', false])->get()->count();

        for($i=0; $i<$questions->count(); $i++) {
            $question = $questions[$i];
            $question->createdAt = $question->created_at->diffForHumans();
            $question->more = \Illuminate\Support\Str::words($question->more, 7, '...');
            $question->status = $question->status ? 'Enabled' : 'Disabled';
        }

        return response()->json([
            "data" => compact('questions', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('imports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImportRequest $request)
    {
        $questionImport = (new \App\QuestionImport)->store($request);
        return response()->json([
            "data" => $questionImport
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $waiting = QuestionImport::where('imported', false)->get();
        foreach ($waiting as $q) {
            $q->import();
        }
        return response()->json([
            "data" => true
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    *
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $import = QuestionImport::findOrFail($id);
        $import->delete();
        return response()->json([
            "data" => $id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy_all()
    {
        $waiting = QuestionImport::where('imported', false);
        $waiting->delete();
        return response()->json([
            "data" => true
        ], 200);
    }
}