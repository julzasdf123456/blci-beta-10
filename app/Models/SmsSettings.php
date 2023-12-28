<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSettings extends Model
{
    public $table = 'SMS_Settings';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'Bills',
        'NoticeOfDisconnection',
        'ServiceConnectionReception',
        'InspectionCreation',
        'PaymentApproved',
        'InspectionToday'
    ];

    protected $casts = [
        'id' => 'string',
        'Bills' => 'string',
        'NoticeOfDisconnection' => 'string',
        'ServiceConnectionReception' => 'string',
        'InspectionCreation' => 'string',
        'PaymentApproved' => 'string',
        'InspectionToday' => 'string'
    ];

    public static array $rules = [
        'Bills' => 'nullable|string|max:10',
        'NoticeOfDisconnection' => 'nullable|string|max:10',
        'ServiceConnectionReception' => 'nullable|string|max:10',
        'InspectionCreation' => 'nullable|string|max:10',
        'PaymentApproved' => 'nullable|string|max:10',
        'InspectionToday' => 'nullable|string|max:10',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
