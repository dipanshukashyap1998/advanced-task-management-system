<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return UserResource
     */
    public function show(string $id)
    {
        $user = $this->userService->getUserById((int) $id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param string $id
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->updateUser((int) $id, $request->validated());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $this->userService->deleteUser((int) $id);
        return response()->json(null, 204);
    }
}
