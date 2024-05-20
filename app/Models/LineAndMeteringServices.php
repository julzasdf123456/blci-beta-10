<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineAndMeteringServices extends Model
{
    public $table = 'CRM_LineAndMeteringServices';

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'TypeOfService',
        'MeterSealNumber',
        'IsLeadSeal',
        'MeterStatus',
        'MeterNumber',
        'Multiplier',
        'MeterType',
        'MeterBrand',
        'Notes',
        'ServiceDate',
        'UserId',
        'PrivateElectrician',
        'LineLength',
        'ConductorType',
        'ConductorSize',
        'ConductorUnit',
        'Status',
        'AccountNumber',
    ];

    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'TypeOfService' => 'string',
        'MeterSealNumber' => 'string',
        'IsLeadSeal' => 'string',
        'MeterStatus' => 'string',
        'MeterNumber' => 'string',
        'Multiplier' => 'decimal:2',
        'MeterType' => 'string',
        'MeterBrand' => 'string',
        'Notes' => 'string',
        'ServiceDate' => 'datetime',
        'UserId' => 'string',
        'PrivateElectrician' => 'string',
        'LineLength' => 'string',
        'ConductorType' => 'string',
        'ConductorSize' => 'string',
        'ConductorUnit' => 'string',
        'Status' => 'string',
        'AccountNumber' => 'string',
    ];

    public static array $rules = [
        'ServiceConnectionId' => 'nullable|string|max:50',
        'TypeOfService' => 'nullable|string|max:300',
        'MeterSealNumber' => 'nullable|string|max:50',
        'IsLeadSeal' => 'nullable|string|max:50',
        'MeterStatus' => 'nullable|string|max:50',
        'MeterNumber' => 'nullable|string|max:50',
        'Multiplier' => 'nullable|numeric',
        'MeterType' => 'nullable|string|max:50',
        'MeterBrand' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'ServiceDate' => 'nullable',
        'UserId' => 'nullable|string|max:50',
        'PrivateElectrician' => 'nullable|string|max:300',
        'LineLength' => 'nullable|string|max:50',
        'ConductorType' => 'nullable|string|max:50',
        'ConductorSize' => 'nullable|string|max:50',
        'ConductorUnit' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AccountNumber' => 'nullable|string',
    ];

    
}
