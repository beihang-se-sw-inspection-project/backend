<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentPostRelationshipController extends Controller
{
    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONAPIService $service)
    {

        $this->service = $service;
    }

    public function index(Comment $comment)
    {
        return $this->service->fetchRelationship($comment, 'posts');
    }

    public function update(JSONAPIRelationshipRequest $request, Comment $comment)
    {
        return $this->service->updateToOneRelationship($comment, 'posts', $request->input('data.id'));
    }
}
