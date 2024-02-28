<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingFromText extends Model
{
    public $table = 'Billing_ReadingFromText';

    public $fillable = [
        'id',
        'HouseNumber',
        'ConsumerName',
        'OldAccountNo',
        'NewMeterNumber',
        'ReadingMonth',
        'ServicePeriod',
        'LastReading',
        'OldMeterNumber',
        'MeterReader',
        'Status',
        'ReadingScheduleDate'
    ];

    protected $casts = [
        'id' => 'string',
        'HouseNumber' => 'string',
        'ConsumerName' => 'string',
        'OldAccountNo' => 'string',
        'NewMeterNumber' => 'string',
        'ReadingMonth' => 'date',
        'ServicePeriod' => 'date',
        'LastReading' => 'decimal:2',
        'OldMeterNumber' => 'string',
        'MeterReader' => 'string',
        'Status' => 'string',
        'ReadingScheduleDate' => 'string',
    ];

    public static array $rules = [
        'HouseNumber' => 'nullable|string|max:60',
        'ConsumerName' => 'nullable|string|max:500',
        'OldAccountNo' => 'nullable|string|max:50',
        'NewMeterNumber' => 'nullable|string|max:100',
        'ReadingMonth' => 'nullable',
        'ServicePeriod' => 'nullable',
        'LastReading' => 'nullable|numeric',
        'OldMeterNumber' => 'nullable|string|max:100',
        'MeterReader' => 'nullable|string|max:100',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ReadingScheduleDate' => 'nullable|string',
    ];

    
}
