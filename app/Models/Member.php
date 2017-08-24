<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function getHomePhoneAttribute($value)
    {
        return $this->formatPhone($value);
    }

    public function getCellPhoneAttribute($value)
    {
        return $this->formatPhone($value);
    }

    protected function formatPhone($phone)
    {
        $result = null;
        if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
            $result = '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
        }

        return $result;
    }
}