<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Relationships
 */
class Relationship extends Model
{
    protected $table = 'relationships';

    public $timestamps = false;

    protected $fillable = [
        'relationship'
    ];

    protected $guarded = [];
}