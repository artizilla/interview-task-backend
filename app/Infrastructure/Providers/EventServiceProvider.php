<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Approval\Application\Listeners\ApprovalRequestNotification;
use App\Modules\Approval\Application\Listeners\RejectionRequestNotification;
use App\Modules\Invoices\Application\Listeners\InvoiceApproveNotification;
use App\Modules\Invoices\Application\Listeners\InvoiceRejectNotification;
use App\Modules\Invoices\Events\InvoiceApprovalRequested;
use App\Modules\Invoices\Events\InvoiceRejectionRequested;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
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
        EntityApproved::class => [
            InvoiceApproveNotification::class,
        ],
        EntityRejected::class => [
            InvoiceRejectNotification::class,
        ],
        InvoiceApprovalRequested::class => [
            ApprovalRequestNotification::class,
        ],
        InvoiceRejectionRequested::class => [
            RejectionRequestNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
