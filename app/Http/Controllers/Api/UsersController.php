<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\QueryExpanders\UsersQueryExpander;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"user"},
     *     summary="Fetch all users",
     *     description="Returns a single user",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="limiting number of returned users",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="sort by any of user fields (email, name, username)",
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
     *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *     )
     *  )
     * )
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $queryExpander = new UsersQueryExpander($request);
        $usersQuery = $queryExpander->apply(User::query());

        return response()->json($usersQuery->get());
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"user"},
     *     summary="Find user by ID",
     *     description="Returns a single user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user to return",
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
     *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *     )
     *  ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function detail(Request $request, $id)
    {
        $user = User::query()->find($id);
        if(!$user) {
            return response()->json(['message' => 'Not Found!'], 404);
        }
        return response()->json($user);
    }


    /**
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function userPosts(Request $request, $id)
    {
        $user = User::query()->find($id);
        if(!$user) {
            return response()->json([
                'message' => 'user not found'
            ]);
        }
        return response()->json($user->posts);
    }
}
