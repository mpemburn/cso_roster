<?php

namespace App\Listeners;

use App\Events\MemberJoined;
use App\Contracts\Repositories\DuesRepositoryContract;

class MakeDuesPayment
{
    protected $duesRepository;

    /**
     * MakeDuesPayment constructor.
     * @param DuesRepositoryContract $duesRepository
     */
    public function __construct(DuesRepositoryContract $duesRepository)
    {
        $this->duesRepository = $duesRepository;
    }

    /**
     * Handle the event.
     *
     * @param  MemberJoined  $event
     * @return void
     */
    public function handle(MemberJoined $event)
    {
        $memberId = $event->member->id;

        $this->duesRepository->makePayment($event->data, $memberId);
    }
}
