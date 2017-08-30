<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Format;

/**
 * Class Dues
 */
class Dues extends Model
{
    protected $table = 'dues';

    public $timestamps = true;

    protected $fillable = [
        'member_id',
        'paid_date',
        'paid_amount',
        'calendar_year',
        'helmet_fund'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getPaidDateAttribute($value)
    {
        return Format::formatDate(Format::SHORT_DATE, $value);
    }

    public function getPaidAmountAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }
}