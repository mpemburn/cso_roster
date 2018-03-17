<?php

namespace App\Listeners;

use App\Events\MemberJoined;
use Illuminate\Support\Facades\Mail;

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
        $member = $event->member;

        $failed = [];
        try {
            Mail::send('emails.notify_secretary', [
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'member_url' => url('/') . '/member/details/' . $member->id,
            ], function ($mailer) use ($member) {
                $mailer->from(config('mail.from.address') , 'Chesapeake Spokes Website');
                $mailer->to('admin@chesapeakespokes.org', 'admin@chesapeakespokes.org')->subject('A New Member has joined');
            });
            $failed = Mail::failures();
        } catch (\Exception $e) {
            return ['email' => 'Unable to send to this address'];
        }

    }
}
