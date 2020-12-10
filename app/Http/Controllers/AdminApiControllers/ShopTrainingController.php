<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Requests\Web\TrainingRequest;
use App\ShopAuthor;
use App\ShopQuizz;
use App\ShopTraining;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopTrainingController extends Controller
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
        $trainings = ShopTraining::where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = ShopTraining::where('name', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$trainings->count(); $i++) {
            $training = $trainings[$i];
            $training->createdAt = $training->created_at->diffForHumans();
            $training->description = \Illuminate\Support\Str::words($training->description, 7, '...');
            $training->is_published = $training->is_published ? 'Yes' : 'No';
            $training->tags = (isset($training->tags) && sizeof($training->tags) != 0) ? implode(', ', $training->tags) : 'No tags';
            $training->quizzesCount = $training->questions()->count();
            $training->questionsCount = $training->questions()->count();
        }
        return response()->json([
            "data" =>compact('trainings', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $authors = ShopAuthor::pluck('name', 'id');
        foreach ($authors as $key => $value) {
            $authors[$key] = "#" . $key . " " . $value;
        }
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        return response()->json([
            "data" =>compact('authors', 'difficulty_array')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TrainingRequest $request)
    {
        $training = (new \App\ShopTraining)->createTraining($request);
        $training->setTags($request);
        $training->storePicture($request);
        return response()->json([
            "data" =>$training
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShopTraining  $shopTraining
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShopTraining  $shopTraining
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $shopTraining = ShopTraining::findOrFail($id);
        $shopAuthors = ShopAuthor::pluck('name', 'id');
        foreach ($shopAuthors as $key => $value) {
            $shopAuthors[$key] = "#" . $key . " " . $value;
        }
        $tags = '';
        if ($shopTraining->tags != null)
            $tags = implode(",", $shopTraining->tags);
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        $difficulty_array_index = 1;
        switch ($shopTraining->difficulty)
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
            "data" =>compact('shopTraining', 'shopAuthors', 'tags', 'difficulty_array', 'difficulty_array_index')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShopTraining  $shopTraining
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, TrainingRequest $request)
    {
        $training = ShopTraining::findOrFail($id);
        $training->updateTraining($request);
        $training->setTags($request);
        if($request->hasFile('image_url')){
            $training->storePicture($request);
        }        
        $trainings = ShopTraining::all();
        $idx = $trainings->search(function ($train) use ($training) {
            return $train->id == $training->id;
        });
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShopTraining  $shopTraining
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $training = ShopTraining::findOrFail($id);
        $training->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}