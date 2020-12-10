<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Config;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\GroupConfigRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view config')->only('index', 'data', 'show');
        $this->middleware('permission:create config')->only('create', 'store');
        $this->middleware('permission:edit config')->only('edit', 'update');
        $this->middleware('permission:delete config')->only('destroy');
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
        $configs = Config::where('key', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = Config::where('key', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$configs->count(); $i++) {
            $config = $configs[$i];
            $config->updatedAt = $config->updated_at->diffForHumans();
            $config->createdAt = $config->created_at->diffForHumans();
        }
        return response()->json([
            "data" =>compact('configs', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('config.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupConfigRequest $request)
    {
        $config = Config::create([
           'key' => $request->input('key'),
           'value' => $request->input('value'),
        ]);
        return response()->json([
            "data" =>$config
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
        $config = Config::findOrFail($id);
        return response()->json([
            "data" =>$config
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupConfigRequest $request, $id)
    {
        $config = Config::findOrFail($id);
        $config->update([
            'key' => $request->input('key'),
            'value' => $request->input('value'),
        ]);
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
        $config = Config::findOrFail($id);
        $config->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
