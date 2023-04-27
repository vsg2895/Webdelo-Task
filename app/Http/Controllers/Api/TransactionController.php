<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreRequest;
use App\Http\Requests\Transaction\TransactionFiltersRequest;
use App\Http\Requests\Transaction\UpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Mail\TransactionCreatedMail;
use App\Models\Transaction;
use App\Models\User;
use Facades\App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(TransactionFiltersRequest $request): AnonymousResourceCollection
    {
        $transactions = count($request->all()) > 1 ? TransactionService::filtering() : Transaction::all();

        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return TransactionResource
     */
    public function store(StoreRequest $request): TransactionResource
    {
        $transaction = TransactionService::store($request->validated());
        $user = User::findOrFail($transaction->author_id);
        Mail::to($user->email)->send(new TransactionCreatedMail($transaction->name, $user));
        Log::info('Email Prepare To Sending ', [$user->email]);

        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     * @return TransactionResource
     */
    public function show(Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Transaction $transaction
     * @return TransactionResource
     */
    public function update(UpdateRequest $request, Transaction $transaction): TransactionResource
    {
        $transaction = TransactionService::update($transaction, $request->validated());

        return new TransactionResource($transaction);
    }

    /**
     * Remove transaction
     *
     * @param Transaction $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return Response::success();
    }

    /**
     * Get auth user transactions amount sum.
     * @return \Illuminate\Http\JsonResponse
     */
    public function authUserTransactionsSum()
    {
        $result = TransactionService::sumByAmount();
        $response = request()->user()->name . ' transactions sum is - ' . $result->sum;

        return Response::success($response);
    }
}
