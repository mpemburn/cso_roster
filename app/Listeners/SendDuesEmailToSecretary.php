<?php

namespace App\Listeners;

use App\Events\MemberRenewed;
use Illuminate\Support\Facades\Mail;

class SendDuesEmailToSecretary
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
     * @param  MemberRenewed  $event
     * @return void
     */
    public function handle(MemberRenewed $event)
    {
        $member = $event->member;

        $failed = [];
        try {
            Mail::send('emails.notify_secretary', [
                'text' => $member->first_name . ' ' . $member->last_name . ' just made a dues payment.',
                'member_url' => url('/') . '/member/details/' . $member->id,
            ], function ($mailer) use ($member) {
                $mailer->from(config('mail.from.address') , 'Chesapeake Spokes Website');
                $mailer->to('admin@chesapeakespokesclub.org')
                    ->subject('A Member has renewed: ' . $member->first_name . ' ' . $member->last_name);
            });
            $failed = Mail::failures();
        } catch (\Exception $e) {
            return ['email' => 'Unable to send to this address'];
        }
    }
}
