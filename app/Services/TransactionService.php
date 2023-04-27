<?php

namespace App\Services;

use App\Contracts\TransactionInterface;
use App\Models\Transaction;
use App\QueryFilters\CreatedFilter;
use App\QueryFilters\ExpensesFilter;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionInterface
{
    private $startDate;
    private $endDate;

    public function __construct()
    {
        $this->startDate = request()->start_date ?? null;
        $this->endDate = request()->end_date ?? null;
    }

    public function store($data): Transaction
    {
        return Transaction::create($data);
    }

    public function update(Transaction $transaction, $data): Transaction
    {
        $transaction->update($data);
        return $transaction;
    }

    public function filtering()
    {
        return app(Pipeline::class)
            ->send(Transaction::query())
            ->through([
                ExpensesFilter::class,
                CreatedFilter::class
            ])
            ->thenReturn()
            ->get();
    }

    public function sumByAmount()
    {
        $selectQuery = DB::table('transactions')
            ->selectRaw('sum(transactions.amount) as sum');
        $userTransactionsSum = !is_null($this->startDate) && !is_null($this->endDate)
            ? $this->queryByDate($selectQuery)->where('transactions.author_id', request()->user()->id)
            : $selectQuery->where('transactions.author_id', request()->user()->id);

        return $userTransactionsSum->first();
    }

    public function queryByDate($query)
    {
        return $query->whereBetween('transactions.created_at', [$this->startDate, $this->endDate]);
    }

}
