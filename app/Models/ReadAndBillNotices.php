<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadAndBillNotices extends Model
{
    public $table = 'Billing_ReadAndBillNotices';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Notes',
        'ServicePeriod',
        'UserId',
        'Zone',
        'Block'
    ];

    protected $casts = [
        'id' => 'string',
        'Notes' => 'string',
        'ServicePeriod' => 'date',
        'UserId' => 'string',
        'Zone' => 'string',
        'Block' => 'string'
    ];

    public static array $rules = [
        'Notes' => 'nullable|string|max:5000',
        'ServicePeriod' => 'nullable',
        'UserId' => 'nullable|string|max:50',
        'Zone' => 'nullable|string|max:50',
        'Block' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
