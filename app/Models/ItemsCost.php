<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ItemsCost
 * @package App\Models
 * @version June 1, 2023, 2:29 pm PST
 *
 * @property integer $cst_no
 * @property string $rrno
 * @property string $it_code
 * @property number $ave_qty
 * @property number $qty
 * @property string $uom
 * @property number $cst
 * @property number $amt
 * @property string $rdate
 * @property string $rtime
 * @property string $categ
 * @property string $specs
 */
class ItemsCost extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'tblitems_cost';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "mysql";

    public $fillable = [
        'cst_no',
        'rrno',
        'it_code',
        'ave_qty',
        'qty',
        'uom',
        'cst',
        'amt',
        'rdate',
        'rtime',
        'categ',
        'specs'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'cst_no' => 'integer',
        'rrno' => 'string',
        'it_code' => 'string',
        'ave_qty' => 'decimal:2',
        'qty' => 'decimal:2',
        'uom' => 'string',
        'cst' => 'decimal:6',
        'amt' => 'decimal:6',
        'rdate' => 'string',
        'rtime' => 'string',
        'categ' => 'string',
        'specs' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cst_no' => 'required|integer',
        'rrno' => 'nullable|string|max:15',
        'it_code' => 'required|string|max:15',
        'ave_qty' => 'nullable|numeric',
        'qty' => 'required|numeric',
        'uom' => 'required|string|max:15',
        'cst' => 'required|numeric',
        'amt' => 'required|numeric',
        'rdate' => 'required|string|max:30',
        'rtime' => 'required|string|max:20',
        'categ' => 'required|string|max:15',
        'specs' => 'nullable|string|max:254'
    ];

    
}
