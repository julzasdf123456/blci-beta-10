<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MeterReaderTracks
 * @package App\Models
 * @version December 6, 2021, 4:07 pm PST
 *
 * @property string $TrackNameId
 * @property string $Latitude
 * @property string $Longitude
 */
class MeterReaderTracks extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_MeterReaderTracks';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'TrackNameId',
        'Latitude',
        'Longitude',
        'Captured'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'TrackNameId' => 'string',
        'Latitude' => 'string',
        'Longitude' => 'string',
        'Captured' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'TrackNameId' => 'nullable|string|max:100',
        'Latitude' => 'nullable|string|max:50',
        'Longitude' => 'nullable|string|max:50',
        'Captured' => 'nullable'
    ];

    
}
