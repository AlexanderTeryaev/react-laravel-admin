<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Requests\CloseReportRequest;
use App\Question;
use App\QuestionReport;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view question|create question|edit question|delete question']);
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
        $question_reports = QuestionReport::withoutGlobalScope('admin_current_group')->where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->with(['user', 'question'])->get();
        $total_count = QuestionReport::withoutGlobalScope('admin_current_group')->where('name', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$question_reports->count(); $i++) {
            $question_report = $question_reports[$i];
            $question_report->createdAt = $question_report->created_at->diffForHumans();
        }
        
        return response()->json([
            "data" =>compact('question_reports', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = QuestionReport::findOrFail($id);
        $question = Question::withoutGlobalScope('status')->findOrFail($report->question_id);
        $user = User::findOrFail($report->user_id);
        $user_reports = QuestionReport::select('id', 'question_id', 'report', 'status')->where('user_id', $report->user_id)->where('id', '!=', $report->id)->get();
        $question_reports = QuestionReport::select('id', 'user_id', 'report', 'status')->where('question_id', $report->question_id)->where('id', '!=', $report->id)->get();
        return response()->json([
            "data" =>compact('report', 'question', 'user_reports', 'question_reports', 'user')
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
        $report = QuestionReport::findOrFail($id);
        $report->update([
            'admin_id' => Auth::user()->id,
            'status' => 'resolved',
            'author_id' => null]);
        $user = User::findOrFail($report->user_id);
        $user->update(['reputation' => $user->reputation + 1]);
        if ($user->one_signal_id)
        {
            $question = Question::findOrFail($report->question_id);
            $user->sendCongratNotification(true, $question->question);
        }
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close(CloseReportRequest $request, $id)
    {
        $report = QuestionReport::findOrFail($id);
        $report->update([
            'admin_id' => Auth::user()->id,
            'review' => $request->input('review'),
            'status' => 'closed',
            'author_id' => null]);
        $user = User::findOrFail($report->user_id);
        $user->update(['reputation' => $user->reputation - 1]);
        if ($user->one_signal_id)
        {
            $question = Question::findOrFail($report->question_id);
            $user->sendCongratNotification(false, $question->question);
        }
        return response()->json([
            "data" =>$id
        ], 200);
    }

    public function delete($id)
    {
        $report = QuestionReport::findOrFail($id);
        $report->delete();
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
        $report = QuestionReport::findOrFail($id);
        $report->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
