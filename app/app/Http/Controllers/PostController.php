<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginatedRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(protected PostRepository $postRepository) {}

    public function index(PaginatedRequest $request)
    {
        $posts = $this->postRepository->getAllPosts($request->input('per_page', 10));

        return PostResource::collection($posts);
    }

    public function store(PostCreateRequest $request): PostResource
    {
        $post = $this->postRepository->createPost($request->validated());

        return new PostResource($post);
    }

    public function show(int $id): PostResource
    {
        $post = $this->postRepository->getPostById($id);

        return new PostResource($post);
    }

    public function update(PostUpdateRequest $request, $id): PostResource
    {
        $post = $this->postRepository->updatePost($id, $request->validated());

        return new PostResource($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->postRepository->deletePost($id);

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
