<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return User::class;
    }
}
