<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Insight
{
    public $group_id;
    public $gpr_logo_url;
    public $totalUsers;
    public $lastWeekUsers;
    public $totalUserAnswers;
    public $lastWeekUserAnswers;
    public $topTenGoodQuestions;
    public $topTenBadQuestions;
    public $globalGoodAnswerRatio;
    public $lastWeekGlobalGoodAnswerRatio;
    public $userAnswersAvgPerDay;
    public $lastWeekUserAnswersAvgPerDay;
    public $quizzList;

    public function __construct($group_id = 1)
    {
        $this->group_id = $group_id;
        $this->gpr_logo_url = Group::find($this->group_id)->logo_url;
        $this->totalUsers = DB::table('user_groups')->where('group_id', $group_id)->count();
        $this->lastWeekUsers =  DB::table('user_groups')->where('group_id', $group_id)
            ->where('created_at', '>', Carbon::now()->subDay(7))
            ->count();
        $this->totalUserAnswers = DB::table('user_answers')->where('group_id', $group_id)->count();
        $this->lastWeekUserAnswers = DB::table('user_answers')->where('group_id', $group_id)->where('created_at', '>', Carbon::now()->subDay(7))->count();
        $this->topTenGoodQuestions = $this->getTopTenGoodQuestions();
        $this->topTenBadQuestions = $this->getTopTenBadQuestions();
        $this->globalGoodAnswerRatio = $this->getGlobalGoodAnswerRatio();
        $this->lastWeekGlobalGoodAnswerRatio = $this->getLastWeekGlobalGoodAnswerRatio();
        $this->userAnswersAvgPerDay = $this->getUserAnswersAvgPerDay();
        $this->lastWeekUserAnswersAvgPerDay = $this->getLastWeekUserAnswersAvgPerDay();
        $this->quizzList = $this->getQuizzList(20);
    }

    public function getLastWeekTotalQuestion()
    {
        return DB::table('questions')->where('group_id', $this->group_id)
            ->where('created_at', '>', Carbon::now()->subDay(7))->count();
    }

    public function getTotalQuestion()
    {
        return DB::table('questions')->where('group_id', $this->group_id)->count();
    }

    private function getQuizzList($limit)
    {
        $quizz_name_followers = DB::table('quizzes')
            ->select('quizzes.id', 'quizzes.name', DB::raw('COUNT(user_subscriptions.id) as `followers`'))
            ->join('user_subscriptions', 'quizzes.id', '=', 'user_subscriptions.quizz_id')
            ->where('quizzes.group_id', $this->group_id)
            ->groupBy('quizzes.id')
            ->orderBy('followers', 'desc')
            ->limit($limit)
            ->get();

        foreach ($quizz_name_followers as $quizz)
        {
            $quizz->success_rate = DB::table('user_answers')
                ->select(DB::raw('ROUND(COUNT(CASE WHEN result = 1 THEN 1 END) * 100 / COUNT(*)) AS rate'))
                ->whereIn('question_id', function($q) use($quizz) {
                    $q->select('id')->from('questions')->where('quizz_id', $quizz->id);
                })->value('rate');

        }

        return $quizz_name_followers;
    }

    private function getTopTenGoodQuestions()
    {
        return DB::table('user_answers')
            ->select('user_answers.question_id', 'questions.question',
                DB::raw('count(user_answers.id) as `total`'),
                DB::raw('ROUND(COUNT(CASE WHEN user_answers.result = 1 THEN 1 END) * 100 / COUNT(user_answers.id)) AS `rate`'))
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.group_id', $this->group_id)
            ->groupBy('question_id')
            ->orderBy('rate', 'desc')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
    }


    private function getTopTenBadQuestions()
    {
        return DB::table('user_answers')
            ->select('user_answers.question_id', 'questions.question',
                DB::raw('count(user_answers.id) as `total`'),
                DB::raw('ROUND(COUNT(CASE WHEN user_answers.result = 1 THEN 1 END) * 100 / COUNT(user_answers.id)) AS `rate`'))
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.group_id', $this->group_id)
            ->groupBy('question_id')
            ->orderBy('rate', 'asc')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
    }

    private function getGlobalGoodAnswerRatio()
    {
        return DB::table('user_answers')
            ->select(DB::raw('ROUND(COUNT(CASE WHEN result = 1 THEN 1 END) * 100 / COUNT(*)) AS rate'))
            ->where('group_id', $this->group_id)
            ->value('rate');
    }

    private function getLastWeekGlobalGoodAnswerRatio()
    {
        return DB::table('user_answers')
            ->select(DB::raw('ROUND(COUNT(CASE WHEN result = 1 THEN 1 END) * 100 / COUNT(*)) AS rate'))
            ->where('group_id', $this->group_id)
            ->where('created_at', '>', Carbon::now()->subDay(7))
            ->value('rate');
    }


    private function getUserAnswersAvgPerDay()
    {
        // raw query: SELECT ROUND(AVG(rowsPerDay)) AS average FROM ( SELECT COUNT(*) AS rowsPerDay FROM user_answers WHERE group_id = ? GROUP BY user_id, DATE(created_at) ) as sub;
        $sub =  UserAnswer::selectRaw("COUNT(*) AS rowsPerDay")
            ->where('group_id', $this->group_id)
            ->groupBy('user_id', DB::raw("DATE(created_at)"));

        return DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub->getQuery())
            ->selectRaw("round(avg(rowsPerDay)) as average")
            ->value('average');
    }

    private function getLastWeekUserAnswersAvgPerDay()
    {
        // raw query: SELECT ROUND(AVG(rowsPerDay)) AS average FROM ( SELECT COUNT(*) AS rowsPerDay FROM user_answers WHERE group_id = ? GROUP BY user_id, DATE(created_at) ) as sub;
        $sub =  UserAnswer::selectRaw("COUNT(*) AS rowsPerDay")
            ->where('group_id', $this->group_id)
            ->where('created_at', '>', Carbon::now()->subDay(7))
            ->groupBy('user_id', DB::raw("DATE(created_at)"));

        return DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub->getQuery())
            ->selectRaw("round(avg(rowsPerDay)) as average")
            ->value('average');
    }
}
