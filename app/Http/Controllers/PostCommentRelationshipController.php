<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class PostCommentRelationshipController extends Controller
{
    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONAPIService $service)
    {

        $this->service = $service;
    }

    public function index(Post $post)
    {
        return $this->service->fetchRelationship($post, 'comments');
    }

    public function update(JSONAPIRelationshipRequest $request, Post $post)
    {
        return $this->service->updateToManyRelationships($post, 'comments', $request->input('data.*.id'));
    }

}
