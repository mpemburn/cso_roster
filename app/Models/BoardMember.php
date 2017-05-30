<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BoardMember
 */
class BoardMember extends Model
{
    protected $table = 'board_members';

    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'board_role_id',
        'start_date',
        'end_date'
    ];

    protected $guarded = [];

        
}