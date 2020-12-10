<?php

namespace App\Http\Controllers\AdminApicontrollers;

use App\Http\Controllers\Controller;
use App\InsightRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InsightRecipientController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view insight_recipient')->only('index', 'data', 'show');
        $this->middleware('permission:create insight_recipient')->only('create', 'store');
        $this->middleware('permission:edit insight_recipient')->only('edit', 'update');
        $this->middleware('permission:delete insight_recipient')->only('destroy');
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
        $insight_recipients = InsightRecipient::withoutGlobalScope('admin_current_group')->where('email', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = InsightRecipient::withoutGlobalScope('admin_current_group')->where('email', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$insight_recipients->count(); $i++) {
            $insight_recipient = $insight_recipients[$i];
            $insight_recipient->createdAt = $insight_recipient->created_at->diffForHumans();
        }
        return response()->json([
            "data" => compact('insight_recipients', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        return view('insight_recipients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('insight_recipients')->where(function ($query) {
                    $query->where('group_id', Auth::user()->current_group);
                })
            ]
        ]);
        $insight = InsightRecipient::create([
            'email' => $request->input('email'),
            'group_id' => \Auth::user()->current_group,
        ]);
        $insight->update($request->all());
        return response()->json([
            "data" => $insight
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InsightRecipient  $insightRecipient
     * @return \Illuminate\Http\Response
     */
    public function show(InsightRecipient $insightRecipient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InsightRecipient  $insightRecipient
     * @return \Illuminate\Http\Response
     */
    public function edit(InsightRecipient $insightRecipient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InsightRecipient  $insightRecipient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InsightRecipient $insightRecipient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $insight = InsightRecipient::findOrFail($id);
        $insight->delete();
        return response()->json($id, 200);
    }

}
