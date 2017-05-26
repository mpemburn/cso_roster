<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleUser
 */
class RoleUser extends Model
{
    protected $table = 'role_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role_id',
        'roles_id',
        'users_id',
        'users_members_id',
        'users_members_users_id'
    ];

    protected $guarded = [];

        
}