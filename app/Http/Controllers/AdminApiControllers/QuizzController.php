<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Author;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuizzRequest;
use App\Quizz;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class QuizzController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view quizz')->only('index', 'data', 'show');
        $this->middleware('permission:create quizz')->only('create', 'store');
        $this->middleware('permission:edit quizz')->only('edit', 'update');
        $this->middleware('permission:delete quizz')->only('destroy');
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

        $tempQuery = Quizz::withoutGlobalScope('admin_current_group')->where('name', 'LIKE', "%{$searchText}%");
        if ($group !=0) {
            $tempQuery = $tempQuery->where('group_id', '=' , $group);
        }
        $total_count = $tempQuery->get()->count();
        $quizzes = $tempQuery->offset($offset*$limit)->take($limit)->get();

        for($i=0; $i<$quizzes->count(); $i++) {
            $quizz = $quizzes[$i];
            $quizz->createdAt = $quizz->created_at->diffForHumans();
            $quizz->questionsCount = $quizz->questions->count();
            $quizz->geolocalization = ($quizz->is_geolocalized) ?  $quizz->latitude . ';' . $quizz->longitude . ' Rad: ' . $quizz->radius : 'No geo';
            $quizz->tags = (isset($quizz->tags) && sizeof($quizz->tags) != 0) ? implode(', ', $quizz->tags) : 'No tags';
            $quizz->isPublished = ($quizz->is_published) ? 'Published' : 'Disabled';
        }

        return response()->json([
            "data" =>compact('quizzes', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::pluck('name', 'id');
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        return response()->json([
            "data" => compact('authors', 'difficulty_array')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\QuizzRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuizzRequest $request)
    {
        $quizz  = (new \App\Quizz)->createQuizz($request);
        $quizz->setTags($request);
        $quizz->storePicture($request);
        $quizz->storeDefaultPicture($request);
        
        $quizz->geolocation($request);
        return response()->json([
            "data" =>$quizz
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quizz  $quizz
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quizz = Quizz::with('questions')->withoutGlobalScope('status')->with('author')->findOrFail($id);
        $tags = '';
        if ($quizz->tags != null)
            $tags = implode(",", $quizz->tags);
        $quizz->userCount = $quizz->users()->count();
        return response()->json([
            "data" =>compact('quizz', 'tags')
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quizz  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quizz = Quizz::withoutGlobalScope('status')->findOrFail($id);
        $authors = Author::pluck('name', 'id');
        $tags = '';
        if ($quizz->tags != null)
            $tags = implode(",", $quizz->tags);
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        $difficulty_array_index = 1;
        switch ($quizz->difficulty)
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
            default:
                break;
        }
        return response()->json([
            "data" =>compact('quizz', 'authors', 'tags', 'difficulty_array', 'difficulty_array_index')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\QuizzRequest  $request
     * @param  \App\Quizz  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuizzRequest $request, $id)
    {
        $quizz = Quizz::withoutGlobalScope('status')->findOrFail($id);
        $quizz->updateQuizz($request);
        $quizz->setTags($request);
        if($request->hasFile('image_url')){
            $quizz->storePicture($request);
        }
        if($request->hasFile('default_questions_image')){
            $quizz->storeDefaultPicture($request);
        }
        $quizz->geolocation($request);
        return response()->json([
            "data" =>$quizz
        ], 200);
    }
    
    // public function update(QuizzRequest $request, $id)
    // {
        
    // }

    public function delete($id)
    {
        $quiz = Quizz::withoutGlobalScope('status')->findOrFail($id);
        $quiz->delete();
        return redirect(route('quizzes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quizz  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function questions_image($id, Request $request)
    {
        $quizz = Quizz::findOrFail($id);
        $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:1000',
                Rule::dimensions()->ratio(16 / 10)
            ],
        ]);

        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());
        $image_name = 'questions/qs_bg_' . Auth::user()->id . '_' . Auth::user()->current_group . '_qz-' . $quizz->id . '_' . Carbon::now()->timestamp . '.' . $ext;
        Storage::put($image_name, file_get_contents($file), 'public');

        $quizz->questions()->update(['bg_url' => $image_name]);
        return response()->json([
            "data" =>true
        ], 200);
    }
}
