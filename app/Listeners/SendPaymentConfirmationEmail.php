<?php

namespace App\Listeners;

use App\Events\MemberRenewed;
use Illuminate\Support\Facades\Mail;

class SendPaymentConfirmationEmail
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
     * @param  MemberRenewed  $event
     * @return mixed
     */
    public function handle(MemberRenewed $event)
    {
        $member = $event->member;

        $failed = [];
        try {
            Mail::send('emails.payment_received', [
                'first_name' => $member->first_name,
            ], function ($mailer) use ($member) {
                $mailer->from(config('mail.from.address') , 'Chesapeake Spokes');
                $mailer->to($member->email, $member->first_name . ' ' . $member->last_name)->subject('We received your payment to Chesapeake Spokes');
            });
            $failed = Mail::failures();
        } catch (\Exception $e) {
            return ['email' => 'Unable to send to this address'];
        }

    }
}
