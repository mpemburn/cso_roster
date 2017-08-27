<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Prefix
 */
class Prefix extends Model
{
    protected $table = 'prefixes';

    public $timestamps = false;

    protected $fillable = [
        'prefix'
    ];

    protected $guarded = [];


}