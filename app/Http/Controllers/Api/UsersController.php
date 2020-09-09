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
}
