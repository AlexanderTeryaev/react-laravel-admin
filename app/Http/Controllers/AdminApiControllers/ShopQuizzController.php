<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Requests\Web\QuizzTrainingRequest;
use App\ShopAuthor;
use App\ShopQuizz;
use App\Http\Controllers\Controller;
use App\ShopTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ShopQuizzController extends Controller
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
        $quizzes = ShopQuizz::where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->with(['training', 'author'])->get();
        $total_count = ShopQuizz::where('name', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$quizzes->count(); $i++) {
            $quizz = $quizzes[$i];
            $quizz->createdAt = $quizz->created_at->diffForHumans();
            $quizz->questionsCount = $quizz->questions->count();
            $quizz->tags = (isset($quizz->tags) && sizeof($quizz->tags) != 0) ? implode(', ', $quizz->tags) : 'No tags';
        }

        return response()->json([
            "data" =>compact('quizzes', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $trainings = ShopTraining::pluck('name', 'id');
        foreach ($trainings as $key => $value) {
            $trainings[$key] = "#" . $key . " " . $value;
        }
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        return response()->json([
            "data" => compact( 'trainings', 'difficulty_array')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(QuizzTrainingRequest $request)
    {
        $training = ShopTraining::findOrFail($request->input('training_id'));
        $quizz =  ShopQuizz::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty') + 1,
            'shop_training_id' => $training->id,
            'shop_author_id' => $training->author->id,
        ]);
        $quizz->setTags($request);
        $quizz->storePicture($request);
        return response()->json([
            "data" =>$quizz
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShopQuizz  $shopQuizz
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quizz = ShopQuizz::with('questions')->with('author')->findOrFail($id);
        $tags = '';
        $quizz->status = $quizz->status ? 'Enabled' : 'Disabled';
        $quizz->trainingName = $quizz->training->name;
        return response()->json([
            "data" =>compact('quizz')
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $shopQuizz = ShopQuizz::findOrFail($id);
        $shopTrainings = ShopTraining::pluck('name', 'id');
        foreach ($shopTrainings as $key => $value) {
            $shopTrainings[$key] = "#" . $key . " " . $value;
        }
        $tags = '';
        if ($shopQuizz->tags != null)
            $tags = implode(",", $shopQuizz->tags);
        $difficulty_array = ['EASY', 'MEDIUM', 'HARD'];
        $difficulty_array_index = 1;
        switch ($shopQuizz->difficulty)
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
            "data" =>compact('shopQuizz', 'shopTrainings','tags', 'difficulty_array', 'difficulty_array_index')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, QuizzTrainingRequest $request)
    {
        $quizz = ShopQuizz::findOrFail($id);
        $training = ShopTraining::findOrFail($request->input('training_id'));

        $quizz->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty') + 1,
            'shop_training_id' => $training->id,
            'shop_author_id' => $training->author->id,
        ]);
        $quizz->setTags($request);
        if($request->hasFile('image_url')){
            $quizz->storePicture($request);
        }
        return response()->json([
            "data" =>$quizz
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Int $id
     * @return \Illuminate\Http\Response
     *
     */
    public function destroy($id)
    {
        $quizz = ShopQuizz::findOrFail($id);
        $quizz->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }

    public function questions_image($id, Request $request)
    {
        $quizz = ShopQuizz::findOrFail($id);

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
