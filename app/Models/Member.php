<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Member
 */
class Member extends Model
{
    protected $table = 'members';

    public $timestamps = true;

    protected $fillable = [
        'is_active',
        'first_name',
        'middle_name',
        'last_name',
        'prefix',
        'suffix',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'email',
        'home_phone',
        'cell_phone',
        'emergency_contact',
        'emergency_phone_1',
        'emergency_phone_2',
        'comments',
        'member_since_date',
        'google_group_date',
        'user_id'
    ];


    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}