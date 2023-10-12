<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionDateAdjustments extends Model
{
    public $table = 'Cashier_CollectionDateAdjustments';

    protected $connection = 'sqlsrv';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'UserId',
        'DateAssigned',
        'Notes',
        'Status',
        'AssignedBy',
    ];

    protected $casts = [
        'id' => 'string',
        'UserId' => 'string',
        'DateAssigned' => 'date',
        'Notes' => 'string',
        'Status' => 'string',
        'AssignedBy' => 'string'
    ];

    public static array $rules = [
        'UserId' => 'nullable|string|max:50',
        'DateAssigned' => 'nullable',
        'Notes' => 'nullable|string|max:500',
        'Status' => 'nullable|string|max:50',
        'AssignedBy' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
