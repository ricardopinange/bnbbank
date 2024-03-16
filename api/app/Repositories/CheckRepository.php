<?php

namespace App\Repositories;

use App\Models\Check;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class CheckRepository extends AbstractRepository
{
    protected static $model = Check::class;

    public static function all(Request $filter):LengthAwarePaginator
    {
        $query = self::$model::select();

        if (isset($filter->year) && isset($filter->month)) {
            $startDate = Carbon::createFromFormat('Y-m-d', 
                "{$filter->year}-{$filter->month}-01")->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', 
                "{$filter->year}-{$filter->month}-01")->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (isset($filter->situation)) {
            $query->where('situation', $filter->situation);
        }

        return $query->orderByDesc('created_at')->paginate();
    }

    public static function control(Request $filter):LengthAwarePaginator
    {
        $query = self::$model::select(
            'id','user_id','amount','situation','created_at'
        )->with('user:id,name');

        if (isset($filter->year) && isset($filter->month)) {
            $startDate = Carbon::createFromFormat('Y-m-d', 
                "{$filter->year}-{$filter->month}-01")->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', 
                "{$filter->year}-{$filter->month}-01")->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (isset($filter->situation)) {
            $query->where('situation', $filter->situation);
        }

        return $query->orderBy('created_at')->paginate();
    }

    public static function details(string $id):?Model
    {
        return self::$model::select()
            ->with('user:id,name,email,account')
            ->whereId($id)->first();
    }
}