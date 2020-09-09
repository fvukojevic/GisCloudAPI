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
     * Fetch all stored users
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
     * Fetch specific user by id
     *
     * @return JsonResponse
     */
    public function detail(Request $request, $id)
    {
        $user = User::query()->find($id);
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
            return response()->json(null);
        }
        return response()->json($user->posts);
    }
}
