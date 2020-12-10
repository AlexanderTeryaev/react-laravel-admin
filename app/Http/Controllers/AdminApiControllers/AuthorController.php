<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Group;
use App\Http\Controllers\Controller;


use App\Author;
use Illuminate\Http\Request;
use App\Http\Requests\Web\AuthorRequest;
use App\Question;
use App\Quizz;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view author')->only('index', 'data', 'show');
        $this->middleware('permission:create author')->only('create', 'store');
        $this->middleware('permission:edit author')->only('edit', 'update');
        $this->middleware('permission:delete author')->only('destroy');
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
        $tempQuery = Author::withoutGlobalScope('admin_current_group')->where('name', 'LIKE', "%{$searchText}%");
        if ($group !=0) {
            $tempQuery = $tempQuery->where('group_id', '=' , $group);
        }
        $total_count = $tempQuery->get()->count();
        $authors = $tempQuery->offset($offset*$limit)->take($limit)->get();
        
        for($i=0; $i<$authors->count(); $i++) {
            $author = $authors[$i];
            $author->createdAt = $author->created_at->diffForHumans();
            $author->description = \Illuminate\Support\Str::words($author->description, 7, '...');
        }

        return response()->json([
            "data" =>compact('authors', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            "data" =>true
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorRequest $request)
    {
        $author = Author::create([
            'name' => $request->input('name'),
            'function' => $request->input('function'),
            'description' => $request->input('description'),
            'pic_url' => 'None',
            'fb_link' => ($request->input('fb_link')) ? $request->input('fb_link') : null,
            'twitter_link' => ($request->input('twitter_link')) ? $request->input('twitter_link') : null,
            'website_link' => ($request->input('website_link')) ? $request->input('website_link') : null,
            'group_id' => \Auth::user()->current_group,
        ]);

        $author->storePicture($request);
        return response()->json([
            "data" =>$author
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
        $author = Author::findOrFail($id);
        $group = Group::findOrFail($author->group_id);

        $quizzes = Quizz::where('author_id', $author->id)->get();
        $questions = Question::with(['quizz'=>function($query){return $query->select('id','name');}])->whereIn('quizz_id', $quizzes->pluck('id'))->get();

        $pic = env('IMAGES_URL') . $author->pic_url;
        return response()->json([
            "data" =>compact('author', 'group', 'pic','quizzes', 'questions')
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
        $author = Author::findOrFail($id);
        return response()->json([
            "data" =>compact('author')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorRequest $request, $id)
    {
        $author = Author::findOrFail($id);
        $author->update([
            'name' => $request->input('name'),
            'function' => $request->input('function'),
            'description' => $request->input('description'),
            'fb_link' => ($request->input('fb_link')) ? $request->input('fb_link') : null,
            'twitter_link' => ($request->input('twitter_link')) ? $request->input('twitter_link') : null,
            'website_link' => ($request->input('website_link')) ? $request->input('website_link') : null,
         ]);
        if($request->hasFile('pic_url')){
            $author->storePicture($request);
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
        $author = Author::findOrFail($id);
        $author->softDeletes();
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

    }
}