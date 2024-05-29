<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSettings extends Model
{
    public $table = 'System_Settings';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'PasswordDaysExpire'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public static array $rules = [
        'PasswordDaysExpire' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
