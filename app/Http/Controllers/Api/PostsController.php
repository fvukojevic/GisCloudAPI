<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\QueryExpanders\PostsQueryExpander;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 */
class PostsController extends Controller
{
    /**
     * Fetch all stored users
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $queryExpander = new PostsQueryExpander($request);
        $postsQuery = $queryExpander->apply(Post::query());

        return response()->json($postsQuery->get());
    }
}
