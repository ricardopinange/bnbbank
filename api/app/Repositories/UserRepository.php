<?php

namespace App\Repositories;

use App\Models\User;

abstract class UserRepository extends AbstractRepository
{
    protected static $model = User::class;
}