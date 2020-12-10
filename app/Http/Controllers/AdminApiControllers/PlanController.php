<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view plan')->only('index', 'data', 'show');
        $this->middleware('permission:create plan')->only('create', 'store');
        $this->middleware('permission:edit plan')->only('edit', 'update');
        $this->middleware('permission:delete plan')->only('destroy');
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
        $plans = Plan::where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = Plan::where('name', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$plans->count(); $i++) {
            $plan = $plans[$i];
            if (isset($plan->features) && sizeof($plan->features) != 0) {
                $plan->features = implode(', ', $plan->features);
            } else {
                $plan->features =  'No features';
            }
            if ($plan->users_limit == 0) {
                $plan->users_limit = '∞';
            } else {
                $plan->users_limit =  $plan->users_limit;
            }
            $plan->updatedAt = $plan->updated_at->diffForHumans();
            $plan->createdAt = $plan->created_at->diffForHumans();
        }

        return response()->json([
            "data" =>compact('plans', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plan = Plan::create([
            'name' => $request->input('name'),
            'features' => explode(',', $request->input('features')),
            'price' => floatval($request->input('price')),
            'users_limit' => intval($request->input('users_limit')),
            'plan_id' => $request->input('plan_id'),
        ]);
        return response()->json([
            "data" =>$plan
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
        $plan = Plan::findOrFail($id);
        $features = implode(",", $plan->features);
        return response()->json([
            "data" =>compact( 'plan', 'features')
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
        $plan = Plan::findOrFail($id);
        $plan->update([
            'name' => $request->input('name'),
            'features' => explode(',', $request->input('features')),
            'price' => floatval($request->input('price')),
            'users_limit' => intval($request->input('users_limit')),
            'plan_id' => $request->input('plan_id'),
        ]);
        return response()->json([
            "data" =>$plan
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
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
