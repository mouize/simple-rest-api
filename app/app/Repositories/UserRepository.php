<?php

namespace App\Repositories;

use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository
{
    public function getAllUsers($perPage = 10)
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['name', 'created_at'])
            ->with('posts')
            ->paginate($perPage);
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function getUserById(int $id): User
    {
        return User::with(['posts', 'comments'])->findOrFail($id);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function deleteUser(int $id): void
    {
        User::findOrFail($id)->delete();
    }
}
