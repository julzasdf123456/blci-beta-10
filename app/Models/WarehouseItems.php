<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class WarehouseItems
 * @package App\Models
 * @version June 13, 2023, 4:09 pm PST
 *
 * @property string $reqno
 * @property integer $ent_no
 * @property string $tdate
 * @property string $itemcd
 * @property string $ascode
 * @property number $qty
 * @property string $uom
 * @property number $cst
 * @property number $amt
 * @property integer $itemno
 * @property string $rdate
 * @property string $rtime
 */
class WarehouseItems extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'tblor_line';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "mysql";

    public $fillable = [
        'reqno',
        'ent_no',
        'tdate',
        'itemcd',
        'ascode',
        'qty',
        'uom',
        'cst',
        'amt',
        'itemno',
        'rdate',
        'rtime'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'reqno' => 'string',
        'ent_no' => 'integer',
        'tdate' => 'string',
        'itemcd' => 'string',
        'ascode' => 'string',
        'qty' => 'decimal:2',
        'uom' => 'string',
        'cst' => 'decimal:6',
        'amt' => 'decimal:6',
        'itemno' => 'integer',
        'rdate' => 'string',
        'rtime' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'reqno' => 'nullable|string|max:45',
        'ent_no' => 'required|integer',
        'tdate' => 'nullable|string|max:45',
        'itemcd' => 'nullable|string|max:25',
        'ascode' => 'nullable|string|max:12',
        'qty' => 'nullable|numeric',
        'uom' => 'nullable|string|max:12',
        'cst' => 'nullable|numeric',
        'amt' => 'nullable|numeric',
        'itemno' => 'required|integer',
        'rdate' => 'nullable|string|max:45',
        'rtime' => 'nullable|string|max:25'
    ];

    
}
