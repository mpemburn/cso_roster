<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Guest
 */
class Guest extends Model
{
    protected $table = 'guests';

    public $timestamps = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone'
    ];

    protected $guarded = [];

    public function getRulesAttribute()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
        ];
    }

}