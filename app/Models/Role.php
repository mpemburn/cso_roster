<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 */
class Role extends Model
{
    protected $table = 'roles';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    protected $guarded = [];

        
}