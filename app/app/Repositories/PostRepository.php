<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class PostRepository
{
    public function getAllPosts($perPage = 10): LengthAwarePaginator
    {
        return QueryBuilder::for(Post::class)
            ->allowedFilters(['user_id'])
            ->allowedSorts(['title', 'created_at'])
            ->with(['user', 'comments'])
            ->paginate($perPage);
    }

    public function createPost(array $data): Post
    {
        return Post::create($data);
    }

    public function getPostById(int $id): Post
    {
        return Post::with(['user', 'comments'])->findOrFail($id);
    }

    public function updatePost(int $id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update($data);

        return $post;
    }

    public function deletePost(int $id): void
    {
        Post::findOrFail($id)->delete();
    }
}
