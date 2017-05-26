<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionRole
 */
class PermissionRole extends Model
{
    protected $table = 'permission_role';

    public $timestamps = false;

    protected $fillable = [
        'permission_id',
        'role_id',
        'permissions_id',
        'roles_id'
    ];

    protected $guarded = [];

        
}