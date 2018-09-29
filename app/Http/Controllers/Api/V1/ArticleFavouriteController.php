<?php

namespace App\Http\Controllers\Api\V1;

use App\ArticleFavourite;
use App\Exceptions\ApiException;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class ArticleFavouriteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $favourite = ArticleFavourite::where('user_id', $request->input('user_id'))->orderBy('created_at', 'DESC')->get();
        return $this->respond($favourite);
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
        if (!$request->input('article_id'))
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'article_id should be filled'
            );
        if (ArticleFavourite::where(['article_id' => $request->input('article_id'), 'user_id' => $request->input('user_id')])->exists())
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'article_id is exists'
            );
        ArticleFavourite::create([
            'article_id' => $request->input('article_id'),
            'user_id' => $request->input('user_id'),
            'created_at' => strtotime("now")
        ]);
        return $this->respond(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
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
    public function destroy($id, Request $request)
    {
        if (!ArticleFavourite::where(['id' => $id, 'user_id' => $request->input('user_id')])->exists()) {
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'Plz check your favourite'
            );
        }
        ArticleFavourite::where(['id' => $id, 'user_id' => $request->input('user_id')])->delete();
        return $this->respond(['status' => 'success']);
    }
}