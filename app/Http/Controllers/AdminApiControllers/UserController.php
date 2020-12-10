<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Category;
use App\Group;
use App\Http\Controllers\Controller;

use App\Http\Requests\Web\NotificationRequest;
use App\Question;
use App\QuestionReport;
use App\Quizz;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json($request->user()->load('roles.permissions'));
    }

    public function __construct()
    {
        $this->middleware('permission:view user')->only('index', 'data', 'show');
        $this->middleware('permission:create user')->only('create', 'store');
        $this->middleware('permission:edit user')->only('edit', 'update');
        $this->middleware('permission:delete user')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $users = User::All();
        $limit = $request->rowsPerPage;
        $offset = $request->currentPage;
        $searchText = $request->searchText;
        $users = User::where('username', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = User::where('username', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$users->count(); $i++) {
            $user = $users[$i];
            $user->answerCount = \App\UserAnswer::where('user_id', $user->id)->count();
            $user->subscriptionCount = \App\UserSubscription::where('user_id', $user->id)->count();
            $user->createdAt = $user->created_at->diffForHumans();
            $user->updatedAt = $user->updated_at->diffForHumans();
        }
        return response()->json([
            "data" =>compact('users', 'total_count')
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $groups = $user->groups;

        $quizzes = DB::table('user_subscriptions')->select('user_subscriptions.id', 'quizzes.id AS quizz_id', 'groups.id AS group_id', 'groups.name AS group_name', 'quizzes.name AS quizz_name', 'user_subscriptions.created_at')
                            ->join('groups', 'user_subscriptions.group_id', '=', 'groups.id')
                            ->join('quizzes', 'user_subscriptions.quizz_id', '=', 'quizzes.id')
                            ->where('user_subscriptions.user_id', '=', $user->id)->get();

        $answers = DB::table('user_answers')->select('user_answers.id', 'groups.id AS group_id', 'groups.name AS group_name', 'questions.id as question_id', 'questions.question', 'user_answers.created_at', 'user_answers.result', 'user_answers.is_enduro')
                        ->join('groups', 'user_answers.group_id', '=', 'groups.id')
                        ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                        ->where('user_id', '=', $user->id)->get();

        $screens = ['Quiz', 'Category', 'Featured', 'Author', 'Answer', 'More'];
        $quizzesP = DB::table('user_subscriptions')->select('user_subscriptions.id', 'user_subscriptions.user_id as sub_user_id','quizzes.id AS notif_id', 'groups.id AS group_id', 'groups.name AS group_name', 'quizzes.name AS quizz_name', 'user_subscriptions.created_at')
            ->join('groups', 'user_subscriptions.group_id', '=', 'groups.id')
            ->join('quizzes', 'user_subscriptions.quizz_id', '=', 'quizzes.id')
            ->where('user_subscriptions.user_id', '=', $user->id)->pluck('quizz_name', 'notif_id');
        $indexScreen = 0;
        return response()->json([
            "data" => compact('user', 'groups', 'quizzes', 'answers', 'screens', 'indexScreen', 'quizzesP')
        ], 200);
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
        $user = User::findOrFail($id);
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name')
        ]);
        return response()->json($user, 200);
    }

    public function leftgroup($id, $groupid)
    {
        $user = User::findOrFail($id);

        if ($user->current_group == $groupid)
        {
            $user->update(['current_group' => 1]);
        }

        DB::table('user_groups')->where('group_id', $groupid)->where('user_id', $id)->delete();
        return response()->json([
            "data" => $id
        ], 200);
    }

    private function userQuizzProgressPercentage(Quizz $quizz, User $user): int
    {
        $questions_ids = Question::withoutGlobalScope('admin_current_group')->where('quizz_id', $quizz->id)->pluck('id');
        $good_answered_questions = $user->answers()
            ->distinct('question_id')
            ->where('result', true)
            ->whereIn('question_id', $questions_ids)
            ->count('question_id');

        if ($questions_ids->count() == 0)
            return 0;
        return round($good_answered_questions / $questions_ids->count() * 100);
    }

    public function traning_doc($id, $group_id)
    {
        $user = User::findOrFail($id);

        if (!$user->isInGroup($group_id))
            abort(404);

        $group = Group::findOrFail($group_id);

        $categories = Category::with(['quizzes' => function ($query) {
            $query->withoutGlobalScope('admin_current_group');
        }])->withoutGlobalScope('admin_current_group')->where('group_id', $group_id)->get();

        foreach ($categories as $category)
        {
            foreach ($category->quizzes as $quizz)
                $quizz->user_progres = $this->userQuizzProgressPercentage($quizz, $user);

            if ($category->quizzes->count() == 0)
                $category->user_progress = 0;
            $category->user_progress = round($category->quizzes->where('user_progres', '>', '70')->count() / $category->quizzes->count() * 100);
        }

        $quizzes = Quizz::with(['questions' => function ($query) {
            $query->withoutGlobalScope('admin_current_group');
        }])->withoutGlobalScope('admin_current_group')->where('group_id', $group_id)->get();

        $quizzes_in_difficulty = collect();
        foreach ($quizzes as $quizz)
        {
            $good_answers = $user->answers()->where('result', true)->whereIn('question_id', $quizz->questions->pluck('id'))->count();
            $bad_answers = $user->answers()->where('result', false)->whereIn('question_id', $quizz->questions->pluck('id'))->count();
            if ($bad_answers > $good_answers)
            {
                $quizz->good_answers_percentage = $good_answers / $quizz->questions->count() * 100;
                $quizz->good_answers = $good_answers;
                $quizz->bad_answers = $bad_answers;
                $quizzes_in_difficulty->push($quizz);
            }
        }

        $user_answers = DB::table('user_answers')->selectRaw('YEAR(created_at) AS \'Year\', MONTH(created_at) AS \'Month\', DAY(created_at) AS \'Day\', COUNT(*) AS \'useranswer\'')
            ->groupBy(DB::raw('DAY(created_at), MONTH(created_at), YEAR(created_at)'))
            ->orderBy('Year')
            ->orderBy('Month')
            ->orderBy('Day')
            ->where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->get();
        $months_name = array();
        $monthly_question_data = array();
        foreach ($user_answers as $question)
        {
            array_push($months_name, "\"{$question->Day}/{$question->Month}/{$question->Year}\"");
            array_push($monthly_question_data, $question->useranswer);
        }
        $monthly_chart_data = array(
            'months' => $months_name,
            'answers' => $monthly_question_data,
        );

        return response()->json([
            "data" => compact('user', 'group', 'categories', 'quizzes_in_difficulty', 'monthly_chart_data')
        ], 200);
    }

}
