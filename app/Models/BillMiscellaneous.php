<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillMiscellaneous extends Model
{
    public $table = 'Billing_BillMiscellaneous';

    protected $connection = 'sqlsrv';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Name',
        'Description',
        'Balance',
        'Operation', // DEDUCT, ADD
        'Status',
        'Terms', // MONTHLY, ANNUALLY, ONE-TIME
        'Notes',
        'EndDate',
        'ServiceAccountId',
    ];

    protected $casts = [
        'id' => 'string',
        'Name' => 'string',
        'Description' => 'string',
        'Balance' => 'decimal:2',
        'Operation' => 'string',
        'Status' => 'string',
        'Terms' => 'string',
        'Notes' => 'string',
        'EndDate' => 'string',
        'ServiceAccountId' => 'string',
    ];

    public static array $rules = [
        'Name' => 'nullable|string|max:200',
        'Description' => 'nullable|string|max:1000',
        'Balance' => 'nullable|numeric',
        'Operation' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'Terms' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'EndDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ServiceAccountId' => 'nullable|string',
    ];

    
}
