<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\FeaturedOrdered;
use App\Http\Controllers\Controller;
use App\Featured;
use App\Group;
use Illuminate\Http\Request;
use App\Http\Requests\Web\FeaturedRequest;
use App\Quizz;

class FeaturedController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view featured')->only('index', 'data', 'show');
        $this->middleware('permission:create featured')->only('create', 'store');
        $this->middleware('permission:edit featured')->only('edit', 'update');
        $this->middleware('permission:delete featured')->only('destroy');
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
        $tempQuery = Featured::withoutGlobalScope('admin_current_group')->where('name', 'LIKE', "%{$searchText}%");
        if ($group !=0) {
            $tempQuery = $tempQuery->where('group_id', '=' , $group);
        }
        $total_count = $tempQuery->get()->count();
        $featureds = $tempQuery->offset($offset*$limit)->take($limit)->get();

        for($i=0; $i<$featureds->count(); $i++) {
            $featured = $featureds[$i];
            $featured->description = \Illuminate\Support\Str::words($featured->description, 7, '...');
            $featured->createdAt = $featured->created_at->diffForHumans();
            $featured->is_published = $featured->is_published ? 'Enabled' : 'Disabled';
        }
        
        return response()->json([
            "data" =>compact('featureds', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quizzes = Quizz::select('name', 'id')->get();
        return response()->json([
            "data" =>compact('quizzes')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeaturedRequest $request)
    {
        $imagename = 'None';
        $featured = Featured::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'pic_url' => $imagename,
            'group_id' => \Auth::user()->current_group,
            'is_published' => true,
            'order_id' => $request->input('order_id'),
        ]);
        $featured->quizzes()->attach($request->input('quizzes'));
        $featured->storePicture($request);
        return response()->json([
            "data" =>$featured
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
        $featured = Featured::findOrFail($id);
        $featured->status = $featured->is_published;
        $groups = Group::pluck('name', 'id');
        $quizzes_in = $featured->quizzes->pluck('id')->toArray();
        $quizzes = Quizz::select('name', 'id')->get();
        return response()->json([
            "data" =>compact('featured', 'groups', 'quizzes', 'quizzes_in')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeaturedRequest $request, $id)
    {
        $featured = Featured::findOrFail($id);
        $featured->update($request->all());
        $featured->update([
            'is_published' => (($request->input('status') == '0') ||
                ($request->input('status') == '1' && $featured->is_published == true)) ? true : false,
        ]);

        if($request->hasFile('pic_url')){
            $featured->storePicture($request);
        }        
        $featured->quizzes()->sync($request->input('quizzes'));
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
        $featured = Featured::findOrFail($id);
        $featured->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
