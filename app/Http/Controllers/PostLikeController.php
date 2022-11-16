<?php

namespace App\Http\Controllers;

use App\Models\PostLike;
use App\Http\Requests\JSONAPIRequest;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;


class PostLikeController extends Controller
{
    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'store', 'show'];
    }

    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
        // $this->authorizeResource(Post::class, 'postlikes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->fetchResources(PostLike::class, 'postlikes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {

        $attributes = $request->input('data.attributes');
        $attributes["user_id"] = $request->user()->id;

        return $this->service->createResource(PostLike::class, $attributes, $request->input('data.relationships'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PostLike  $postlike
     * @return \Illuminate\Http\Response
     */
    public function show($postlike)
    {
        return $this->service->fetchResource(PostLike::class, $postlike, 'postlikes');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostLike  $postlike
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, PostLike $postlike)
    {
        return $this->service->updateResource($postlike, $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostLike  $postlike
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostLike $postlike)
    {                

        return $this->service->deleteResource($postlike);
    }
}
