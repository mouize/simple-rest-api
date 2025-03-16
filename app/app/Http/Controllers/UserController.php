<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginatedRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(protected UserRepository $userRepository) {}

    public function index(PaginatedRequest $request): AnonymousResourceCollection
    {
        $users = $this->userRepository->getAllUsers($request->input('per_page', 10));

        return UserResource::collection($users);
    }

    public function store(UserCreateRequest $request): UserResource
    {
        $user = $this->userRepository->createUser($request->validated());

        return new UserResource($user);
    }

    public function show(int $id): UserResource
    {
        $user = $this->userRepository->getUserById($id);

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, $id): UserResource
    {
        $user = $this->userRepository->updateUser($id, $request->validated());

        return new UserResource($user);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userRepository->deleteUser($id);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
