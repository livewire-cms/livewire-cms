<?php

namespace Modules\Backend\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'Modules\Backend\Listeners\LogSuccessfulLogin',
        ],
    ];
}
