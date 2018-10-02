<?php

namespace App\Http\Controllers\Api\V1;

use App\Article;
use App\ArticleFavourite;
use App\Exceptions\ApiException;
use App\Http\Controllers\ApiController;
use App\Inside\Constants;
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
        $article = Article::select(
            "id",
            "cat_id",
            "title",
            "short_description",
            DB::raw("CASE WHEN type_image = '2' THEN image WHEN image != '' THEN (concat ( '" . $request->root() . "/files/article/image/" . "', image) ) ELSE '' END as image"),
            "type_image",
            DB::raw("CASE WHEN type_video = '2' THEN video WHEN video != '' THEN (concat ( '" . $request->root() . "/files/article/video/" . "', video) ) ELSE '' END as video"),
            "type_video",
            DB::raw("CASE WHEN type_audio = '2' THEN audio WHEN audio != '' THEN (concat ( '" . $request->root() . "/files/article/audio/" . "', audio) ) ELSE '' END as audio"),
            "type_audio"
        )->where("cat_id", $catId)->take(10)->skip($skip)->get();
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
    public function store($catId, Request $request)
    {
        if (!$request->input('title'))
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'Plz check your title'
            );
        if ($request->file('image'))
            if (!in_array($request->file('image')->getClientMimeType(), Constants::PHOTO_TYPE))
                throw new ApiException(
                    ApiException::EXCEPTION_BAD_REQUEST_400,
                    'Plz check your image'
                );
        if ($request->file('video'))
            if (!in_array($request->file('video')->getClientMimeType(), Constants::VIDEO_TYPE))
                throw new ApiException(
                    ApiException::EXCEPTION_BAD_REQUEST_400,
                    'Plz check your video'
                );
        if ($request->file('audio'))
            if (!in_array($request->file('audio')->getClientMimeType(), Constants::AUDIO_TYPE))
                throw new ApiException(
                    ApiException::EXCEPTION_BAD_REQUEST_400,
                    'Plz check your audio'
                );
        \Storage::disk('upload')->makeDirectory("article", 755);
        \Storage::disk('upload')->makeDirectory("article/image", 755);
        \Storage::disk('upload')->makeDirectory("article/video", 755);
        \Storage::disk('upload')->makeDirectory("article/audio", 755);
        $image = "";
        if ($request->file('image')) {
            $image = md5(time() . pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->file('image')->getClientOriginalExtension();
            \Storage::disk('upload')->put("article/image/" . $image, \File::get($request->file('image')->getRealPath()));
        }
        $video = "";
        if ($request->file('video')) {
            $video = md5(time() . pathinfo($request->file('video')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->file('video')->getClientOriginalExtension();
            \Storage::disk('upload')->put("article/video/" . $video, \File::get($request->file('video')->getRealPath()));
        }
        $audio = "";
        if ($request->file('audio')) {
            $audio = md5(time() . pathinfo($request->file('audio')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->file('audio')->getClientOriginalExtension();
            \Storage::disk('upload')->put("article/audio/" . $audio, \File::get($request->file('audio')->getRealPath()));
        }
        Article::create([
            "cat_id" => $catId,
            "title" => $request->input("title"),
            "short_description" => $request->input("short_description"),
            "description" => $request->input("description"),
            "image" => $image,
            "type_image" => 1,
            "video" => $video,
            "type_video" => 1,
            "audio" => $audio,
            "type_audio" => 1,
        ]);

        return $this->respond(["status" => "success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($catId, $id, Request $request)
    {
        $article = Article::select(
            "*",
            DB::raw("CASE WHEN type_image = '2' THEN image WHEN image != '' THEN (concat ( '" . $request->root() . "/files/article/image/" . "', image) ) ELSE '' END as image"),
            DB::raw("CASE WHEN type_video = '2' THEN video WHEN video != '' THEN (concat ( '" . $request->root() . "/files/article/video/" . "', video) ) ELSE '' END as video"),
            DB::raw("CASE WHEN type_audio = '2' THEN audio WHEN audio != '' THEN (concat ( '" . $request->root() . "/files/article/audio/" . "', audio) ) ELSE '' END as audio")
        )->where(["cat_id" => $catId, "id" => $id])->first();
        return $this->respond($article);
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

    ///////////////function//////////////////////////////////

    public function search(Request $request)
    {
        $article = Article::select(
            "id",
            "cat_id",
            "title",
            "short_description",
            DB::raw("CASE WHEN type_image = '2' THEN image WHEN image != '' THEN (concat ( '" . $request->root() . "/files/article/image/" . "', image) ) ELSE '' END as image"),
            "type_image",
            DB::raw("CASE WHEN type_video = '2' THEN video WHEN video != '' THEN (concat ( '" . $request->root() . "/files/article/video/" . "', video) ) ELSE '' END as video"),
            "type_video",
            DB::raw("CASE WHEN type_audio = '2' THEN audio WHEN audio != '' THEN (concat ( '" . $request->root() . "/files/article/audio/" . "', audio) ) ELSE '' END as audio"),
            "type_audio"
        )->orWhere('title', 'like', '%' . $request->input('search') . '%')
            ->orWhere('short_description', 'like', '%' . $request->input('search') . '%')->get();
        return $this->respond($article);
    }


    public function getIsFavourite($catId, $id, Request $request)
    {
        $article["is_favourite"] = false;
        if (ArticleFavourite::where(['article_id' => $id, 'user_id' => $request->input('user_id')])->exists())
            $article["is_favourite"] = true;
        return $this->respond($article);
    }


}
