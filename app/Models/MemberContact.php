<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class MemberContact
 */
class MemberContact extends Pivot
{
    protected $table = 'members_contacts';

    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'contact_id'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}