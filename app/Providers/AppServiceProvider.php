<?php

namespace App\Providers;

use App\Contracts\ExchangeInterface;
use App\Contracts\TransactionInterface;
use App\Events\Transaction\CreatedEvent;
use App\Services\JsonExchangeService;
use App\Services\TransactionService;
use App\Services\XmlExchangeService;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        TransactionInterface::class => TransactionService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExchangeInterface::class, function () {
            return request()->has('xml') ? new XmlExchangeService() : new JsonExchangeService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::after(function (JobProcessed $event) {
            $payload = $event->job->payload();
            if ($payload['displayName'] === "App\Mail\TransactionCreatedMail") {
                $obj = unserialize($payload['data']['command']);
                $transactionAuthor = $obj->mailable->user;
                Log::info('Email Successfully Sending To ',[$obj->mailable->user->email]);
                broadcast(new CreatedEvent($transactionAuthor));
            }
        });
    }
}
