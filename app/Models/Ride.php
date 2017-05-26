<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ride
 */
class Ride extends Model
{
    protected $table = 'rides';

    public $timestamps = true;

    protected $fillable = [
        'ride_date',
        'ride_type',
        'description',
        'ride_types_id'
    ];

    protected $guarded = [];

        
}