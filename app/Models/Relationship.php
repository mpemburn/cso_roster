<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Relationships
 */
class Relationships extends Model
{
    protected $table = 'relationships';

    public $timestamps = false;

    protected $fillable = [
        'relationship'
    ];

    protected $guarded = [];
}