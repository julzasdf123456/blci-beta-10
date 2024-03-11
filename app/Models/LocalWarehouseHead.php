<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalWarehouseHead extends Model
{
    public $table = 'WH_Head';

    public $fillable = [
        'id',
        'orderno',
        'ent_no',
        'misno',
        'tdate',
        'emp_id',
        'ccode',
        'dept',
        'pcode',
        'reqby',
        'invoice',
        'orno',
        'purpose',
        'serv_code',
        'account_no',
        'cust_name',
        'tot_amnt',
        'chkby',
        'appby',
        'stat',
        'rdate',
        'rtime',
        'walk_in',
        'appl_no',
        'address',
        'Type'
    ];

    protected $casts = [
        'id' => 'string',
        'orderno' => 'string',
        'misno' => 'string',
        'tdate' => 'date',
        'emp_id' => 'string',
        'ccode' => 'string',
        'dept' => 'string',
        'pcode' => 'string',
        'reqby' => 'string',
        'invoice' => 'string',
        'orno' => 'string',
        'purpose' => 'string',
        'serv_code' => 'string',
        'account_no' => 'string',
        'cust_name' => 'string',
        'tot_amnt' => 'decimal:2',
        'chkby' => 'string',
        'appby' => 'string',
        'stat' => 'string',
        'rdate' => 'date',
        'rtime' => 'string',
        'appl_no' => 'string',
        'address' => 'string',
        'Type' => 'string',
    ];

    public static array $rules = [
        'orderno' => 'nullable|string|max:50',
        'ent_no' => 'nullable',
        'misno' => 'nullable|string|max:50',
        'tdate' => 'nullable',
        'emp_id' => 'nullable|string|max:50',
        'ccode' => 'nullable|string|max:50',
        'dept' => 'nullable|string|max:50',
        'pcode' => 'nullable|string|max:500',
        'reqby' => 'nullable|string|max:50',
        'invoice' => 'nullable|string|max:50',
        'orno' => 'nullable|string|max:50',
        'purpose' => 'nullable|string|max:600',
        'serv_code' => 'nullable|string|max:50',
        'account_no' => 'nullable|string|max:50',
        'cust_name' => 'nullable|string|max:500',
        'tot_amnt' => 'nullable|numeric',
        'chkby' => 'nullable|string|max:50',
        'appby' => 'nullable|string|max:50',
        'stat' => 'nullable|string|max:50',
        'rdate' => 'nullable',
        'rtime' => 'nullable|string|max:50',
        'walk_in' => 'nullable',
        'appl_no' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Type' => 'nullable|string',
    ];

    
}
