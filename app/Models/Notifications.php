<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\IDGenerator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Notifications
 * @package App\Models
 * @version September 4, 2023, 8:24 am PST
 *
 * @property string $id
 * @property string $Source
 * @property string $SourceId
 * @property string $ContactNumber
 * @property string $Message
 * @property string $Status
 * @property string $AIFacilitator
 * @property string $Notes
 */
class Notifications extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'SMS_Notifications';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrv";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Source',
        'SourceId',
        'ContactNumber',
        'Message',
        'Status',
        'AIFacilitator',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Source' => 'string',
        'SourceId' => 'string',
        'ContactNumber' => 'string',
        'Message' => 'string',
        'Status' => 'string',
        'AIFacilitator' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Source' => 'nullable|string|max:255',
        'SourceId' => 'nullable|string|max:300',
        'ContactNumber' => 'nullable|string|max:300',
        'Message' => 'nullable|string|max:3000',
        'Status' => 'nullable|string|max:255',
        'AIFacilitator' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function createFreshSMS($contactNo, $message, $source, $sourceId) {
        Notifications::create([
            'id' => IDGenerator::generateIDandRandString(),
            'Source' => $source,
            'SourceId' => $sourceId,
            'ContactNumber' => $contactNo,
            'Message' => $message,
            'Status' => 'PENDING',
            'AIFacilitator' => 'Reeve',
        ]);
    }
}
