<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;
use App\Question;
use App\Quizz;
use App\User;
use App\UserAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quizz::with('group')->with('authorWithoutGlobalScopes')->withoutGlobalScopes()->limit(4)->orderBy('created_at', 'desc')->get();
        $users = User::with(['groups' => function ($q) {
            return $q->select('name')->pluck('name');
        }])->withCount('quizzes')->limit(5)->orderBy('created_at', 'desc')->get();
        $main = array(
            "user_count" => User::count(),
            "group_count" => Group::count(),
            "question_count" => Question::withoutGlobalScope('admin_current_group')->count(),
            "answer_count" => UserAnswer::count(),
            "quizzes" => $quizzes,
            "users" => $users
        );
        return response()->json([
            "data" => $main
        ], 200);
    }

    public function getStatistics(Request $request)
    {

    }

    public function getUserDailyInstallation() {
        return response()->json([
            "data" => (new \App\User)->dailyInstallation()
        ], 200);
    }

    public function getQuestionDailyAnswered()
    {
        return response()->json([
            "data" => (new \App\User)->dailyQuestionAnswered()
        ], 200);
    }

    function getRepartition()
    {
        return response()->json([
            "data" => (new \App\User)->userRepartitionInstall()
        ], 200);
    }

    public function group_switch($id)
    {
        Group::findOrFail($id);
        $admin = Auth::user();
        $admin->current_group = $id;
        $admin->save();
        return redirect(route('home'));
    }

    public function upload_md_images(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file*' => [
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:1000'
            ],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $images_name_array = array();
        $images = $request->file();
        foreach ($images as  $key => $value)
        {
            $file = $request->file($key);
            $ext = strtolower($file->getClientOriginalExtension());
            $imagename = 'landing/md_' . Auth::user()->id . '_' . Carbon::now()->timestamp . '.' . $ext;
            Storage::put($imagename, file_get_contents($file), 'public');
            $images_name_array[] = env('IMAGES_URL') . $imagename;
        }
        return response()->json($images_name_array);
    }
}
