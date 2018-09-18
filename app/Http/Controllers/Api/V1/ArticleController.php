<?php

namespace App\Http\Controllers\Api\V1;

use App\Article;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($catId, Request $request)
    {
        $skip = 0;
        if ($request->input('page') != null)
            if ($request->input('page') != 0)
                $skip = 10 * $request->input('page');
        $article = Article::select("*", DB::raw("CASE WHEN type_image = '2' THEN image WHEN image != '' THEN (concat ( '" . $request->root() . "/files/article/image/" . "', image) ) ELSE '' END as image"), DB::raw("CASE WHEN type_video = '2' THEN video WHEN video != '' THEN (concat ( '" . $request->root() . "/files/article/video/" . "', video) ) ELSE '' END as video"), DB::raw("CASE WHEN type_audio = '2' THEN audio WHEN audio != '' THEN (concat ( '" . $request->root() . "/files/article/audio/" . "', audio) ) ELSE '' END as audio"))->where("cat_id", $catId)->take(10)->skip($skip)->get();
        return $this->respond($article);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}