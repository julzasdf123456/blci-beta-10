<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class WarehouseHead
 * @package App\Models
 * @version June 13, 2023, 4:05 pm PST
 *
 * @property string $orderno
 * @property integer $ent_no
 * @property string $misno
 * @property string $tdate
 * @property string $emp_id
 * @property string $ccode
 * @property string $dept
 * @property string $pcode
 * @property string $reqby
 * @property string $invoice
 * @property string $orno
 * @property string $purpose
 * @property string $serv_code
 * @property string $account_no
 * @property string $cust_name
 * @property number $tot_amt
 * @property string $chkby
 * @property string $appby
 * @property string $stat
 * @property string $rdate
 * @property string $rtime
 * @property boolean $walk_in
 * @property string $appl_no
 */
class WarehouseHead extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'tblor_head';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $connection = "mysql";

    public $fillable = [
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
        'tot_amt',
        'chkby',
        'appby',
        'stat',
        'rdate',
        'rtime',
        'walk_in',
        'appl_no'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'orderno' => 'string',
        'ent_no' => 'integer',
        'misno' => 'string',
        'tdate' => 'string',
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
        'tot_amt' => 'decimal:2',
        'chkby' => 'string',
        'appby' => 'string',
        'stat' => 'string',
        'rdate' => 'string',
        'rtime' => 'string',
        'walk_in' => 'boolean',
        'appl_no' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'orderno' => 'nullable|string|max:45',
        'ent_no' => 'required|integer',
        'misno' => 'nullable|string|max:10',
        'tdate' => 'nullable|string|max:45',
        'emp_id' => 'nullable|string|max:25',
        'ccode' => 'nullable|string|max:5',
        'dept' => 'nullable|string|max:5',
        'pcode' => 'nullable|string|max:255',
        'reqby' => 'nullable|string|max:45',
        'invoice' => 'nullable|string|max:25',
        'orno' => 'nullable|string|max:45',
        'purpose' => 'nullable|string|max:255',
        'serv_code' => 'nullable|string|max:12',
        'account_no' => 'nullable|string|max:25',
        'cust_name' => 'required|string|max:100',
        'tot_amt' => 'nullable|numeric',
        'chkby' => 'required|string|max:50',
        'appby' => 'required|string|max:50',
        'stat' => 'nullable|string|max:20',
        'rdate' => 'nullable|string|max:45',
        'rtime' => 'nullable|string|max:25',
        'walk_in' => 'required|boolean',
        'appl_no' => 'nullable|string|max:35'
    ];

    
}
