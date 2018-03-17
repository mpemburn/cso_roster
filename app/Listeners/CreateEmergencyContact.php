<?php

namespace App\Listeners;

use App\Events\MemberJoined;
use App\Contracts\Repositories\ContactRepositoryContract;

class CreateEmergencyContact
{
    protected $contactRepository;

    /**
     * CreateEmergencyContact constructor.
     * @param ContactRepositoryContract $contactRepository
     */
    public function __construct(ContactRepositoryContract $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Handle the event.
     *
     * @param  MemberJoined  $event
     * @return void
     */
    public function handle(MemberJoined $event)
    {
        $contactData = $event->data;
        $contactData['name'] = $contactData['contact_name'];
        $contactData['phone_one'] = $contactData['contact_phone'];

        $this->contactRepository->createNewContact($contactData, $event->member);
    }
}
