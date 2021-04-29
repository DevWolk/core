<?php

namespace Apiato\Core\Abstracts\Events\Providers;

use Apiato\Core\Abstracts\Events\Dispatcher\Dispatcher;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Illuminate\Events\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton('events', fn ($app) => (new Dispatcher($app))->setQueueResolver(fn () => $app->make(QueueFactoryContract::class)));
    }
}
