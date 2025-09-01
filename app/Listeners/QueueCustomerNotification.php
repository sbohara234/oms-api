<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Jobs\SendOrderStatusNotificationJob;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class QueueCustomerNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event): void
    {
         SendOrderStatusNotificationJob::dispatch(
            $event->order,
            $event->oldStatus,
            $event->newStatus
        );
    }
}
