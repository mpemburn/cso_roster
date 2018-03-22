<?php

namespace App\Listeners;

use App\Events\MemberJoined;
use Illuminate\Support\Facades\Mail;

class SendNewMemberEmailToSecretary
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
                'message' => 'A new member has joined Chesapeake Spokes: ' . $member->first_name . ' ' . $member->last_name,
                'member_url' => url('/') . '/member/details/' . $member->id,
            ], function ($mailer) use ($member) {
                $mailer->from(config('mail.from.address') , 'Chesapeake Spokes Website');
                $mailer->to('admin@chesapeakespokesclub.org', 'admin@chesapeakespokesclub.org')
                    ->subject('A New Member has joined: ' . $member->first_name . ' ' . $member->last_name);
            });
            $failed = Mail::failures();
        } catch (\Exception $e) {
            return ['email' => 'Unable to send to this address'];
        }

    }
}
