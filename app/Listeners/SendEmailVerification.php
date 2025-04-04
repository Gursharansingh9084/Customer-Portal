<?php

namespace App\Listeners;
use Illuminate\Support\Facades\Log;

use App\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailVerification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(Registered $event)
    {
        Log::info("Handling SendEmailVerification for User ID: {$event->user->id}");
        $event->user->sendEmailVerificationNotification();
    }
}
