<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\CoinsPack;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class CoinsPackController extends Controller
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
     * @return View
     */
    public function index(Request $request)
    {
        $limit = $request->rowsPerPage;
        $offset = $request->currentPage;
        $searchText = $request->searchText;
        $coins_packs = CoinsPack::where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = CoinsPack::where('name', 'LIKE', "%{$searchText}%")->get()->count();

        for($i=0; $i<$coins_packs->count(); $i++) {
            $coins_pack = $coins_packs[$i];
            $coins_pack->createdAt = $coins_pack->created_at->diffForHumans();
            $coins_pack->updatedAt = $coins_pack->updated_at->diffForHumans();
        }

        return response()->json([
            "data" =>compact('coins_packs', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('coins_packs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirector
     */
    public function store(Request $request)
    {
        $coins_pack = CoinsPack::create([
            'name' => $request->input('name'),
            'coins_quantity' => $request->input('coins_quantity'),
            'price' => floatval($request->input('price')),
        ]);
        return response()->json([
            "data" =>$coins_pack
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CoinsPack  $coinsPack
     * @return \Illuminate\Http\Response
     */
    public function show(CoinsPack $coinsPack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Int $id
     * @return View
     */
    public function edit($id)
    {
        $coins_pack = CoinsPack::findOrFail($id);
        return response()->json([
            "data" =>$coins_pack
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CoinsPack  $coinsPack
     * @return Redirector
     */
    public function update(Request $request, $id)
    {
        $coins_pack = CoinsPack::findOrFail($id);
        $coins_pack->update([
            'name' => $request->input('name'),
            'coins_quantity' => $request->input('coins_quantity'),
            'price' => floatval($request->input('price')),
        ]);
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CoinsPack  $coinsPack
     * @return Redirector
     */
    public function destroy($id)
    {
        $coinsPack = CoinsPack::findOrFail($id);
        $coinsPack->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
