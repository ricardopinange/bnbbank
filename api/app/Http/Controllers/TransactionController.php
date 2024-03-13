<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionFilterRequest;
use App\Http\Requests\TransactionRequest;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransactionFilterRequest $request)
    {
        try {
            $data = TransactionRepository::all($request);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        try {
            $currentBalance = TransactionRepository::currentBalance();

            if ($currentBalance->balance*1 < $request->amount*1) {
                $message = "Insufficient balance for this purchase";
                throw new \Exception($message, Response::HTTP_BAD_REQUEST);
            }

            $data = TransactionRepository::create($request->validated());
            return $this->success($data, 'Transaction successfully added');
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = TransactionRepository::find($id);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, string $id)
    {
        try {
            $data = TransactionRepository::update($id, $request->validated());
            return $this->success($data, 'Transaction successfully updated');
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            TransactionRepository::delete($id);
            return $this->success([], 'Transaction successfully deleted');
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Display current balance.
     */
    public function currentBalance()
    {
        try {
            $data = TransactionRepository::currentBalance();
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Display month balance.
     */
    public function monthBalance(TransactionFilterRequest $request)
    {
        try {
            $data = TransactionRepository::monthBalance($request);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }
}
