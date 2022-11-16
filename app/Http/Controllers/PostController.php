<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\JSONAPIRequest;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
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
        // $this->authorizeResource(Post::class, 'posts');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->fetchResources(Post::class, 'posts');
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

       return $this->service->createResource(Post::class, $attributes, $request->input('data.relationships'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($post)
    {
        return $this->service->fetchResource(Post::class, $post, 'posts');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, Post $post)
    {
        return $this->service->updateResource($post, $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
    
       return $this->service->deleteResource($post);
       
    }
}
