<?php

namespace App\Contracts;

use App\Models\Transaction;

interface TransactionInterface
{
    public function store($data);

    public function update(Transaction $transaction, $data);

    public function filtering();

    public function sumByAmount();

    public function queryByDate($query);

}
