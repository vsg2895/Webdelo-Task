<?php

namespace App\Services;

use App\Contracts\ExchangeInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class XmlExchangeService implements ExchangeInterface
{
    public function getFileContent()
    {
        $res = Http::withHeaders([
            'Content-Type' => 'application/xml',
        ])->get(config('filesystems.paths.external-exchange'))->getBody()->getContents();

        $responseXml = simplexml_load_string($res);

        if ($responseXml instanceof \SimpleXMLElement) {
            $array = json_decode(json_encode((array)$responseXml), TRUE);
            $arrayPosition = array_search(request()->currency, array_column($array['item'], 'targetCurrency'));
        }

        return $array['item'][$arrayPosition];
    }

    public function exchange($currency, $sum): float
    {
        $exchangeContent = $this->getFileContent();
        return Cache::rememberForever('xml_exchange' . $currency, function () use ($currency, $exchangeContent, $sum) {
            $exchangeRate = $exchangeContent['exchangeRate'];

            return (float)$sum * $exchangeRate;
        });

    }

}
