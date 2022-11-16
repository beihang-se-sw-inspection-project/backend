<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentUserRelatedController extends Controller
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
        return $this->service->fetchRelated($comment, 'users');
    }
}
