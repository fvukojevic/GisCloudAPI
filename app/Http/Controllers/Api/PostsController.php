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
     * @OA\Get(
     *     path="/posts",
     *     tags={"post"},
     *     summary="Fetch all posts",
     *     description="Returns all posts",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="limiting number of returned posts",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="sort by any of user fields (title, body)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="sort order (asc, desc)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="posts", type="object", ref="#/components/schemas/Post"),
     *     )
     *  )
     * )
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $queryExpander = new PostsQueryExpander($request);
        $postsQuery = $queryExpander->apply(Post::query());

        return response()->json($postsQuery->get());
    }

    /**
     * @OA\Get(
     *     path="/posts/{id}",
     *     tags={"post"},
     *     summary="Find post by ID",
     *     description="Returns a single post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of post to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *        @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
     *     )
     *  ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function detail(Request $request, $id)
    {
        $post = Post::query()->find($id);
        if(!$post) {
            return response()->json(['message' => 'Not Found!'], 404);
        }
        return response()->json($post);
    }
}
