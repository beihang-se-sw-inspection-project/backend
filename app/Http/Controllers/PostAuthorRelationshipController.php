<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Http\Requests\UpdatePostAuthorRelationshipsRequest;
use App\Http\Resources\AuthorIdentifierResource;
use App\Services\JSONAPIService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostAuthorRelationshipController extends Controller
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
        return $this->service->fetchRelationship($post, 'users');
    }

    public function update(JSONAPIRelationshipRequest $request, Post $post)
    {

        if(Gate::denies('admin-only')){
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->service->updateManyToManyRelationships($post, 'users', $request->input('data.*.id'));
    }
}
