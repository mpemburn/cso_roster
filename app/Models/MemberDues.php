<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberDue
 */
class MemberDues extends Model
{
    protected $table = 'member_dues';

    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'paid_date'
    ];

    protected $guarded = [];

        
}