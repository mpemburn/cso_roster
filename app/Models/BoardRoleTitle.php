<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BoardRoleTitle
 */
class BoardRoleTitle extends Model
{
    protected $table = 'board_role_titles';

    public $timestamps = false;

    protected $fillable = [
        'title'
    ];

    protected $guarded = [];

        
}