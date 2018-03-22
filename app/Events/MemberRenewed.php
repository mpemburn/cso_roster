<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class MemberRenewed
{
    use SerializesModels;

    public $member;
    public $data;

    /**
     * MemberRenewed constructor.
     * @param Model $member
     * @param array $data
     */
    public function __construct(Model $member, array $data)
    {
        $this->member = $member;
        $this->data = $data;
    }

}
