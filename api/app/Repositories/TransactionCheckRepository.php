<?php

namespace App\Repositories;

use App\Models\TransactionCheck;

abstract class TransactionCheckRepository extends AbstractRepository
{
    protected static $model = TransactionCheck::class;
}