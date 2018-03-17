<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Queue\SerializesModels;

class MemberJoined
{
    use SerializesModels;

    public $member;

    /**
     * MemberJoined constructor.
     * @param Model $member
     */
    public function __construct(Model $member)
    {
        $this->member = $member;
    }

}
