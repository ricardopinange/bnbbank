<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

Interface RepositoryInterface
{
    public static function all(Request $filter):LengthAwarePaginator;
    public static function find(string $id):?Model;
    public static function findByFilter(array $attributes):?Model;
    public static function create(array $attributes):?Model;
    public static function update(string $id, array $attributes):?Model;
    public static function delete(string $id):string;
    public static function model():Model;
}
