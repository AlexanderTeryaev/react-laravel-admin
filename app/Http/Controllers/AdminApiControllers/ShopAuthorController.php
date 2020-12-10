<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Author;
use App\Http\Requests\Web\AuthorRequest;
use App\ShopAuthor;
use App\Http\Controllers\Controller;
use App\ShopQuestion;
use Illuminate\Http\Request;

class ShopAuthorController extends Controller
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
        $authors = ShopAuthor::where('name', 'LIKE', "%{$searchText}%")->offset($offset*$limit)->take($limit)->get();
        $total_count = ShopAuthor::where('name', 'LIKE', "%{$searchText}%")->get()->count();
        
        for($i=0; $i<$authors->count(); $i++) {
            $author = $authors[$i];
            $author->createdAt = $author->created_at->diffForHumans();
            $author->description = \Illuminate\Support\Str::words($author->description, 7, '...');
        }

        return response()->json([
            "data" =>compact('authors', 'total_count')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shop_authors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AuthorRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(AuthorRequest $request)
    {
        $author = ShopAuthor::create([
            'name' => $request->input('name'),
            'function' => $request->input('function'),
            'description' => $request->input('description'),
            'pic_url' => 'None',
            'fb_link' => ($request->input('fb_link')) ? $request->input('fb_link') : null,
            'twitter_link' => ($request->input('twitter_link')) ? $request->input('twitter_link') : null,
            'website_link' => ($request->input('website_link')) ? $request->input('website_link') : null,
        ]);
        $author->storePicture($request);
        return response()->json([
            "data" =>$author
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $author = ShopAuthor::findOrFail($id);
        return response()->json([
            "data" =>compact('author')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AuthorRequest  $request
     * @param  Int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, AuthorRequest $request)
    {
        $author = ShopAuthor::findOrFail($id);
        $author->update([
            'name' => $request->input('name'),
            'function' => $request->input('function'),
            'description' => $request->input('description'),
            'fb_link' => ($request->input('fb_link')) ? $request->input('fb_link') : null,
            'twitter_link' => ($request->input('twitter_link')) ? $request->input('twitter_link') : null,
            'website_link' => ($request->input('website_link')) ? $request->input('website_link') : null,
        ]);
        if($request->hasFile('pic_url')){
            $author->storePicture($request);
        }
        return response()->json([
            "data" =>$id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = ShopAuthor::findOrFail($id);
        $author->delete();
        return response()->json([
            "data" =>$id
        ], 200);
    }
}
