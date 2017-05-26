<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberRide
 */
class MemberRide extends Model
{
    protected $table = 'member_rides';

    public $timestamps = false;

    protected $fillable = [
        'ride_id',
        'member_id',
        'guest_id',
        'rides_id',
        'rides_ride_types_id',
        'members_id',
        'members_users_id',
        'guests_id'
    ];

    protected $guarded = [];

        
}