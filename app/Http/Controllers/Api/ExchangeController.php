<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ExchangeInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exchange\ExchangeCurrencyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ExchangeController extends Controller
{
    private $exchangeService;

    public function __construct(ExchangeInterface $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    public function exchange(ExchangeCurrencyRequest $request): JsonResponse
    {
        $result = $this->exchangeService->exchange($request->currency, $request->sum);
        $response = $request->sum . '-USD to equal ' . $result . '-' . $request->currency;

        return Response::success($response);
    }
}
