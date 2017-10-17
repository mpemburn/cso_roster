<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token'
    ];


    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        //'deleted_at'
    ];

    public function getRulesAttribute()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
        ];
    }

    public function getResetRulesAttribute()
    {
        return [
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|confirm',
        ];
    }
}
