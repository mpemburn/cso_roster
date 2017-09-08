<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Format;

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
        'phone_one',
        'phone_two',
        'work_phone',
        'phone_ext'
    ];


    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getPhoneOneAttribute($value)
    {
        return Format::formatPhone($value);
    }

    public function getPhoneTwoAttribute($value)
    {
        return Format::formatPhone($value);
    }

    public function getWorkPhoneAttribute($value)
    {
        return Format::formatPhone($value);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'members_contacts')
            ->using(MemberContact::class)
            ->withTimestamps('created_at', 'updated_at');
    }

    public function getRulesAttribute()
    {
        return [
            'name' => 'required',
            'phone_one' => 'required',
        ];
    }
}