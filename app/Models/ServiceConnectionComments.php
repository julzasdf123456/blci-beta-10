<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceConnectionComments extends Model
{
    public $table = 'CRM_ServiceConnectionComments';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'UserId',
        'Comments'
    ];

    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'UserId' => 'string',
        'Comments' => 'string'
    ];

    public static array $rules = [
        'ServiceConnectionId' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:50',
        'Comments' => 'nullable|string|max:3000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
