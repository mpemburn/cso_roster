<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BoardRole
 */
class BoardRole extends Model
{
    protected $table = 'board_roles';

    public $timestamps = false;

    protected $fillable = [
        'role',
        'board_members_id',
        'board_members_members_id'
    ];

    protected $guarded = [];

        
}