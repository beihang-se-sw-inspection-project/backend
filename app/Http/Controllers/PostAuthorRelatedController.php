<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\AuthorCollection;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class PostAuthorRelatedController extends Controller
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
        return $this->service->fetchRelated($post, 'users');
    }
}
