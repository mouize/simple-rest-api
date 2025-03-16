<?php

namespace App\Repositories;

use App\Models\Comment;
use Spatie\QueryBuilder\QueryBuilder;

class CommentRepository
{
    public function getAllComments($perPage = 10)
    {
        return QueryBuilder::for(Comment::class)
            ->allowedFilters(['user_id', 'post_id'])
            ->allowedSorts(['created_at'])
            ->with(['post', 'user'])
            ->paginate($perPage);
    }

    public function createComment(array $data): Comment
    {
        return Comment::create($data);
    }

    public function getCommentById(int $id): Comment
    {
        return Comment::with(['user', 'post'])->findOrFail($id);
    }

    public function updateComment(int $id, array $data): Comment
    {
        $comment = Comment::findOrFail($id);
        $comment->update($data);

        return $comment;
    }

    public function deleteComment(int $id): void
    {
        Comment::findOrFail($id)->delete();
    }
}
