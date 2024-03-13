<?php

namespace App\Repositories;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class TransactionRepository extends AbstractRepository
{
    protected static $model = Transaction::class;

    public static function all(Request $filter):LengthAwarePaginator
    {
        $startDate = Carbon::createFromFormat('Y-m-d', 
            "{$filter->year}-{$filter->month}-01")->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', 
            "{$filter->year}-{$filter->month}-01")->endOfMonth()->endOfDay();

        $query = self::$model::
            whereBetween('created_at', [$startDate, $endDate]);

        if (isset($filter->type)) {
            $query->where('type', $filter->type);
        }

        return $query->orderByDesc('created_at')->paginate();
    }

    public static function currentBalance()
    {
        return self::$model::
            selectRaw(
                "IFNULL(SUM(IF(type='Credit',amount,-amount)),0) as balance"
            )->first();
    }

    public static function monthBalance($filter)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', 
            "{$filter->year}-{$filter->month}-01")->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', 
            "{$filter->year}-{$filter->month}-01")->endOfMonth()->endOfDay();

        $query = self::$model::
            selectRaw("
                IFNULL(SUM(IF(type='Credit',amount,-amount)),0) as balance,
                IFNULL(SUM(IF(type='Credit',amount,0)),0) as incomes,
                IFNULL(SUM(IF(type='Debit',amount,0)),0) as expenses
            ")
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (isset($filter->type)) {
            $query->where('type', $filter->type);
        }

        return $query->first();
    }
}