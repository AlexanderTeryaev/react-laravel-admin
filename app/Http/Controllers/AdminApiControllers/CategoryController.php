<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Requests\Web\CategoryRequest;
use Illuminate\Http\Request;
use App\Quizz;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view category')->only('index', 'data', 'show');
        $this->middleware('permission:create category')->only('create', 'store');
        $this->middleware('permission:edit category')->only('edit', 'update');
        $this->middleware('permission:delete category')->only('destroy');
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
        $tempQuery = Category::withoutGlobalScope('admin_current_group')->where('name', 'LIKE', "%{$searchText}%");
        if ($group !=0) {
            $tempQuery = $tempQuery->where('group_id', '=' , $group);
        }
        $total_count = $tempQuery->get()->count();
        $categories = $tempQuery->offset($offset*$limit)->take($limit)->get();
        
        for($i=0; $i<$categories->count(); $i++) {
            $category = $categories[$i];
            $category->createdAt = $category->created_at->diffForHumans();
            $category->quizzesCount = $category->quizzes->count();
        }

        return response()->json([
            "data" =>compact('categories', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quizzes = Quizz::select('id', 'name')->get();
        return response()->json([
            "data" =>compact( 'quizzes')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->input('name'),
            'group_id' => \Auth::user()->current_group,
            'logo_url' => 'None',
        ]);
        $category->storePicture($request);
        $category->quizzes()->attach($request->input('quizzes'));
        return response()->json([
            "data" =>$category
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
        $category = Category::findOrFail($id);
        $quizzes_in = $category->quizzes->pluck('id')->toArray();
        $quizzes = Quizz::select('id', 'name')->get();
        return response()->json([
            "data" =>compact( 'category', 'quizzes_in', 'quizzes')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        $category->storePicture($request);
        $category->quizzes()->sync($request->input('quizzes'));
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
        $category = Category::findOrFail($id);
        $category->softDeletes();
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
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}