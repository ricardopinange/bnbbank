<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractRepository implements RepositoryInterface
{
    protected static $model;

    public static function model():Model
    {
        return app(static::$model);
    }

    public static function all(Request $filter):LengthAwarePaginator
    {
        return self::model()::paginate();
    }

    public static function find(string $id):?Model
    {
        return self::model()::find($id);
    }

    public static function findByFilter(array $attributes = []):?Model
    {
        return self::model()::where($attributes)->latest()->first();
    }

    public static function create(array $attributes = []):?Model
    {
        $id = self::model()::create($attributes)->id;
        return self::find($id);
    }

    public static function update(string $id, array $attributes = []):?Model
    {
        $data = self::model()::find($id);

        if (!isset($data)) {
            $message = "Register not found";
            throw new \Exception($message, Response::HTTP_NOT_FOUND);
        }

        $data->update($attributes);
        return self::find($id);
    }

    public static function delete(string $id):string
    {
        $data = self::model()::find($id);

        if (!isset($data)) {
            $message = "Register not found";
            throw new \Exception($message, Response::HTTP_NOT_FOUND);
        }

        return $data->delete();
    }

}