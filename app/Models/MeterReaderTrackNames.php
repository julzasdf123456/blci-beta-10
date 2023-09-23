<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MeterReaderTrackNames
 * @package App\Models
 * @version December 6, 2021, 3:52 pm PST
 *
 * @property string $TrackName
 */
class MeterReaderTrackNames extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_MeterReaderTrackNames';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'TrackName'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'TrackName' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'TrackName' => 'required|string|max:600',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
