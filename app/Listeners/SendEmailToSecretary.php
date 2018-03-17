<?php

namespace App\Listeners;

use App\Events\MemberJoined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailToSecretary
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MemberJoined  $event
     * @return void
     */
    public function handle(MemberJoined $event)
    {
        //
    }
}
