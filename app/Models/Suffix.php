<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Suffix
 */
class Suffix extends Model
{
    protected $table = 'suffixes';

    public $timestamps = false;

    protected $fillable = [
        'suffix'
    ];

    protected $guarded = [];


}