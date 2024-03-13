<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

Interface ControllerInterface
{
    public static function index():string;
    public static function store(Request $request):string;
    public static function edit(string $id):string;
    public static function update(Request $request, string $id):string;
    public static function destroy(string $id):string;
    public static function model():Model;
}
