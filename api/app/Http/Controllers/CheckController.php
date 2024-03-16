<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckApproveRequest;
use App\Http\Requests\CheckControlRequest;
use App\Http\Requests\CheckFilterRequest;
use App\Http\Requests\CheckRequest;
use App\Repositories\CheckRepository;
use App\Repositories\TransactionCheckRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CheckFilterRequest $request)
    {
        try {
            $data = CheckRepository::all($request);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CheckRequest $request)
    {
        try {
            $data = collect([]);

            DB::transaction(function () use ($request, $data) {
                $check = CheckRepository::create($request->validated());

                if ($request->hasFile('picture')) {
                    $file = $request->file('picture');
                    $name = "check/{$check->id}.{$file->extension()}";
                    $path = Storage::disk('s3')->put(
                        $name,
                        file_get_contents($file)
                    );

                    if (!$path) {
                        $message = "The picture cannot be uploaded";
                        throw new \Exception($message, Response::HTTP_BAD_REQUEST);
                    }

                    $data->push(CheckRepository::update($check->id,[
                        'picture' => Storage::disk('s3')->url($name)
                    ]));
                }
            });

            return $this->success($data, 'Check successfully added');
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
            $data = CheckRepository::find($id);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CheckRequest $request, string $id)
    {
        try {
            $data = CheckRepository::update($id, $request->validated());
            return $this->success($data, 'Check successfully updated');
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
            CheckRepository::delete($id);
            return $this->success([], 'Check successfully deleted');
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function control(CheckControlRequest $request)
    {
        try {
            $data = CheckRepository::control($request);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function details(string $id)
    {
        try {
            $data = CheckRepository::details($id);
            return $this->success($data);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Approve the specified resource in storage.
     */
    public function approve(CheckApproveRequest $request, string $id)
    {
        try {
            $data = collect([]);
            $check = CheckRepository::find($id);

            if (empty($check)) {
                $message = "Register not found";
                throw new \Exception($message, Response::HTTP_NOT_FOUND);
            }

            if ($check->situation != 'Pending') {
                $message = "Check already {$check->situation}";
                throw new \Exception($message, Response::HTTP_BAD_REQUEST);
            }

            DB::transaction(function () use ($request, $check, $data) {
                CheckRepository::update($check->id, $request->validated());

                if ($request->situation == 'Accepted') {
                    $transaction = TransactionRepository::create([
                        'user_id' => $check->user_id,
                        'description' => $check->description,
                        'amount' => $check->amount,
                        'type' => 'Credit'
                    ]);

                    TransactionCheckRepository::create([
                        'transaction_id' => $transaction->id,
                        'check_id' => $check->id
                    ]);

                    $data->push($transaction);
                }
            });

            return $this->success($data, 
                "Check successfully {$request->situation}");
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }
}
