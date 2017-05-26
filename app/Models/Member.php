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
        'phone',
        'phone_ext',
        'comments',
        'start_date',
        'end_date',
        'users_id'
    ];

    protected $guarded = [];

        
}