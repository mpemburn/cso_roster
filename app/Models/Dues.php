<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dues
 */
class Dues extends Model
{
    protected $table = 'member_dues';

    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'paid_date',
        'calendar_year',
        'helmet_fund'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}