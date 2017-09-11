<?php

namespace App\Models;

use App\Helpers\Format;
use Illuminate\Database\Eloquent\Model;
use App\Models\BoardRoleTitle;

/**
 * Class BoardRole
 */
class BoardRole extends Model
{
    protected $table = 'board_roles';

    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'board_role_id',
        'start_date',
        'end_date'
    ];

    protected $guarded = [];

    public function getRulesAttribute()
    {
        return [
            'board_role_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];
    }

    public function role()
    {
        return $this->hasOne(BoardRoleTitle::class);
    }

    public function getTitleAttribute($value)
    {
        $boardRole = BoardRoleTitle::findOrNew($this->board_role_id);

        return ($boardRole->exists) ? $boardRole->title : null;
    }

    public function getStartDateAttribute($value)
    {
        return Format::formatDate(Format::SHORT_DATE, $value);
    }

    public function getEndDateAttribute($value)
    {
        return Format::formatDate(Format::SHORT_DATE, $value);
    }

}