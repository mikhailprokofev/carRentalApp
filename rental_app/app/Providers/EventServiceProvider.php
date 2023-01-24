<?php

declare(strict_types=1);

namespace App\Providers;

use App\Module\Import\Event\Listener\Import\ImportInitListener;
use App\Module\Import\Event\Model\Import\Init\ImportInitEvent;
use App\Module\Import\Event\Subscriber\Import\ChangeStatusSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ImportInitEvent::class => [
            ImportInitListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            TestJob::class . '@handle',
            fn ($job) => $job->handle(),
        );
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }

    /**
     * Классы подписчиков для регистрации.
     *
     * @var array
     */
    protected $subscribe = [
//        ChangeDataSubscriber::class,
        ChangeStatusSubscriber::class,
    ];
}
