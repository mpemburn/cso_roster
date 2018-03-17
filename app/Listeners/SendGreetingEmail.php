<?php

namespace App\Listeners;

use App\Events\MemberJoined;
use Illuminate\Support\Facades\Mail;

class SendGreetingEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  MemberJoined  $event
     * @return mixed
     */
    public function handle(MemberJoined $event)
    {
        $member = $event->member;

        $failed = [];
        try {
            Mail::send('emails.joined_greeting', [
                'first_name' => $member->first_name,
            ], function ($mailer) use ($member) {
                $mailer->from(config('mail.from.address') , 'hesapeake Spokes');
                $mailer->to($member->email, $member->first_name . ' ' . $member->last_name)->subject('Welcome New Member!');
            });
            $failed = Mail::failures();
        } catch (\Exception $e) {
            return ['email' => 'Unable to send to this address'];
        }

    }
}
