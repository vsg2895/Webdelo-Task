<?php

namespace App\Contracts;

interface ExchangeInterface
{
    public function getFileContent();

    public function exchange($currency,$sum);

}
