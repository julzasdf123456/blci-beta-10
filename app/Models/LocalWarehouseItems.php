<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalWarehouseItems extends Model
{
    public $table = 'WH_Items';

    public $fillable = [
        'id',
        'reqno',
        'ent_no',
        'tdate',
        'itemcd',
        'ascode',
        'qty',
        'uom',
        'cst',
        'amnt',
        'itemno',
        'rdate',
        'rtime',
        'salesprice'
    ];

    protected $casts = [
        'id' => 'string',
        'reqno' => 'string',
        'tdate' => 'date',
        'itemcd' => 'string',
        'ascode' => 'string',
        'qty' => 'decimal:2',
        'uom' => 'string',
        'cst' => 'decimal:2',
        'amnt' => 'decimal:2',
        'rdate' => 'date',
        'rtime' => 'string',
        'salesprice' => 'decimal:2'
    ];

    public static array $rules = [
        'reqno' => 'nullable|string|max:50',
        'ent_no' => 'nullable',
        'tdate' => 'nullable',
        'itemcd' => 'nullable|string|max:50',
        'ascode' => 'nullable|string|max:50',
        'qty' => 'nullable|numeric',
        'uom' => 'nullable|string|max:50',
        'cst' => 'nullable|numeric',
        'amnt' => 'nullable|numeric',
        'itemno' => 'nullable',
        'rdate' => 'nullable',
        'rtime' => 'nullable|string|max:50',
        'salesprice' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
