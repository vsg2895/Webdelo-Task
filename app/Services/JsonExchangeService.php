<?php

namespace App\Services;

use App\Contracts\ExchangeInterface;
use Illuminate\Support\Facades\Cache;

class JsonExchangeService implements ExchangeInterface
{
    public function getFileContent(): array
    {
        $localDir = config('filesystems.paths.exchange');
        $dir = opendir($localDir);
        $files = [];

        if (file_exists($localDir)) {
            while (false !== ($file = readdir($dir))) {
                if (substr($file, 0, 1) === ".") {
                    continue;
                }
                $files[] = $file;
            }
        }
        $contents = [];
        foreach ($files as $file) {
            $contents[] = json_decode(file_get_contents($localDir . '/' . $file), true);
        }

        return $contents;
    }

    public function exchange($currency, $sum): float
    {
        $exchangeContent = $this->getFileContent();

        return Cache::rememberForever('json_exchange' . $currency, function () use ($currency, $exchangeContent, $sum) {
            $currencyKey = strtolower($currency);
            $currencyData = array_map(function ($ar) use ($currencyKey) {
                return $ar[$currencyKey];
            }, $exchangeContent);

            return (float)$sum * $currencyData[0]['rate'];
        });
    }

}
