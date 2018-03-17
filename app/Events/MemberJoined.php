<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Queue\SerializesModels;

class MemberJoined
{
    use SerializesModels;

    public $member;
    public $data;

    /**
     * MemberJoined constructor.
     * @param Model $member
     */
    public function __construct(Model $member, array $data)
    {
        $this->member = $member;
        $this->data = $data;
    }

}
