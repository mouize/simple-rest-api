<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function __construct(protected CommentRepository $commentRepository) {}

    public function index(PaginatedRequest $request): AnonymousResourceCollection
    {
        $comments = $this->commentRepository->getAllComments($request->input('per_page', 10));

        return CommentResource::collection($comments);
    }

    public function store(CommentCreateRequest $request): CommentResource
    {
        $comment = $this->commentRepository->createComment($request->validated());

        return new CommentResource($comment);
    }

    public function show(int $id): CommentResource
    {
        $comment = $this->commentRepository->getCommentById($id);

        return new CommentResource($comment);
    }

    public function update(CommentUpdateRequest $request, $id): CommentResource
    {
        $comment = $this->commentRepository->updateComment($id, $request->validated());

        return new CommentResource($comment);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->commentRepository->deleteComment($id);

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
