<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data = [], $keys = [], $message = null, $status = 200, $headers = [], $inJson = true) {
            if (!$message) {
                $message = __('Well done!');
            }
            $dataArray = [];
            if (count($keys)) {
                foreach ($keys as $key) {
                    $dataArray[$key] = $data[$key];
                }
            } else {
                $dataArray = [
                    'data' => $data,
                    'message' => $message
                ];
            }
            return $inJson ? response()->json($dataArray, $status)->withHeaders($headers)
                : response($data)->withHeaders($headers);
        });

        Response::macro('error', function ($message = 'Something went wrong!', $status = 400) {
            return response()->json([
                'message' => $message
            ], $status);
        });
    }
}
