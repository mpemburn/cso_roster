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

    public function getPhone1Attribute($value)
    {
        return Format::formatPhone($value);
    }

    public function getPhone2Attribute($value)
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
}