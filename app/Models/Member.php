<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Format;
use App\User;

/**
 * Class Member
 */
class Member extends Model
{
    protected $table = 'members';

    public $timestamps = true;

    protected $fillable = [
        'is_active',
        'first_name',
        'middle_name',
        'last_name',
        'prefix',
        'suffix',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'email',
        'home_phone',
        'cell_phone',
        'emergency_contact',
        'emergency_phone_1',
        'emergency_phone_2',
        'comments',
        'member_since_date',
        'google_group_date',
        'user_id'
    ];


    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'members_contacts')
            ->using(MemberContact::class)
            ->withTimestamps('created_at', 'updated_at');
    }

    public function dues()
    {
        return $this->hasMany(Dues::class);
    }

    public function roles()
    {
        return $this->hasMany(BoardRole::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHomePhoneAttribute($value)
    {
        return Format::formatPhone($value);
    }

    public function getCellPhoneAttribute($value)
    {
        return Format::formatPhone($value);
    }

    public function getSinceAttribute()
    {
        return Format::formatDate(Format::LONG_DATE, $this->member_since_date);
    }

    public function getRulesAttribute()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'email' => 'required|email',
        ];
    }
}