<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function register(UserRequest $request)
    {
        try {
            $data = UserRepository::create($request->validated());
            $credentials = request(['email', 'password']);
            $data->access_token = auth()->attempt($credentials);
            return $this->success($data, 'User successfully added');
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }
}
