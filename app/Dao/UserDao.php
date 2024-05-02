<?php

namespace App\Dao;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserDao
{
    public function getAll(int $paginate = 10): LengthAwarePaginator
    {
        return User::paginate($paginate);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function save(User $user): User
    {
        $user->save();
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}