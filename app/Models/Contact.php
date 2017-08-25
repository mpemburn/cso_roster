<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 */
class Contact extends Model
{
    protected $table = 'contacts';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'relationship',
        'phone_1',
        'phone_2',
        'work_phone',
        'phone_ext'
    ];


    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}