<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use App\QuestionImport;
use App\ShopQuestionImport;
use Illuminate\Http\Request;

class ShopQuestionImportController extends Controller
{
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
        $questions = ShopQuestionImport::where(['question', 'LIKE', "%{$searchText}%"], ['imported', false])->offset($offset*$limit)->take($limit)->get();
        $total_count = ShopQuestionImport::where(['question', 'LIKE', "%{$searchText}%"], ['imported', false])->get()->count();
        
        for($i=0; $i<$questions->count(); $i++) {
            $question = $questions[$i];
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
        return view('shop_imports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $shopQuestionImport = (new \App\ShopQuestionImport)->store($request);
        return response()->json([
            "data" => $shopQuestionImport
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import()
    {
        $waiting = ShopQuestionImport::where('imported', false)->get();
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $import = ShopQuestionImport::findOrFail($id);
        $import->delete();
        return response()->json([
            "data" => $id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy_all()
    {
        $waiting = ShopQuestionImport::where('imported', false);
        $waiting->delete();
        return response()->json([
            "data" => true
        ], 200);
    }
}
