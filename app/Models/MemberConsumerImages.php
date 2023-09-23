<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MemberConsumerImages
 * @package App\Models
 * @version October 11, 2021, 9:51 am PST
 *
 * @property string $ConsumerId
 * @property string $PicturePath
 * @property string $HexImage
 */
class MemberConsumerImages extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MemberConsumerImages';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ConsumerId',
        'PicturePath',
        'HexImage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ConsumerId' => 'string',
        'PicturePath' => 'string',
        'HexImage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ConsumerId' => 'nullable|string|max:50',
        'PicturePath' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'HexImage' => 'nullable|string'
    ];

    
}
