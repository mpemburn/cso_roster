<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RideType
 */
class RideType extends Model
{
    protected $table = 'ride_types';

    public $timestamps = false;

    protected $fillable = [
        'description'
    ];

    protected $guarded = [];

        
}