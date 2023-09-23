<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Tickets
 * @package App\Models
 * @version October 18, 2021, 2:54 pm PST
 *
 * @property string $AccountNumber
 * @property string $ConsumerName
 * @property string $Town
 * @property string $Barangay
 * @property string $Sitio
 * @property string $Ticket
 * @property string $Reason
 * @property string $ContactNumber
 * @property string $ReportedBy
 * @property string $ORNumber
 * @property string $ORDate
 * @property string $GeoLocation
 * @property string $Neighbor1
 * @property string $Neighbor2
 * @property string $Notes
 * @property string $Status
 * @property string|\Carbon\Carbon $DateTimeDownloaded
 * @property string|\Carbon\Carbon $DateTimeLinemanArrived
 * @property string|\Carbon\Carbon $DateTimeLinemanExecuted
 * @property string $UserId
 * @property string $CrewAssigned
 */
class Tickets extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Tickets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'ConsumerName',
        'Town',
        'Barangay',
        'Sitio',
        'Ticket',
        'Reason',
        'ContactNumber',
        'ReportedBy',
        'ORNumber',
        'ORDate',
        'GeoLocation',
        'Neighbor1',
        'Neighbor2',
        'Notes',
        'Status',
        'DateTimeDownloaded',
        'DateTimeLinemanArrived',
        'DateTimeLinemanExecuted',
        'UserId',
        'CrewAssigned',
        'Trash',
        'Office',
        'CurrentMeterNo',
        'CurrentMeterBrand',
        'CurrentMeterReading',
        'KwhRating',
        'PercentError',
        'NewMeterNo',
        'NewMeterBrand',
        'NewMeterReading',
        'ServicePeriod'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'ConsumerName' => 'string',
        'Town' => 'string',
        'Barangay' => 'string',
        'Sitio' => 'string',
        'Ticket' => 'string',
        'Reason' => 'string',
        'ContactNumber' => 'string',
        'ReportedBy' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'GeoLocation' => 'string',
        'Neighbor1' => 'string',
        'Neighbor2' => 'string',
        'Notes' => 'string',
        'Status' => 'string',
        'DateTimeDownloaded' => 'datetime',
        'DateTimeLinemanArrived' => 'datetime',
        'DateTimeLinemanExecuted' => 'datetime',
        'UserId' => 'string',
        'CrewAssigned' => 'string',
        'Trash' => 'string',
        'Office' => 'string',
        'CurrentMeterNo' => 'string',
        'CurrentMeterBrand' => 'string',
        'CurrentMeterReading' => 'string',
        'KwhRating' => 'string',
        'PercentError' => 'string',
        'NewMeterNo' => 'string',
        'NewMeterBrand' => 'string',
        'NewMeterReading' => 'string',
        'ServicePeriod' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'ConsumerName' => 'required|string|max:500',
        'Town' => 'required|string|max:255',
        'Barangay' => 'required|string|max:255',
        'Sitio' => 'nullable|string|max:800',
        'Ticket' => 'required|string|max:255',
        'Reason' => 'nullable|string|max:2000',
        'ContactNumber' => 'required|string|max:100',
        'ReportedBy' => 'nullable|string|max:200',
        'ORNumber' => 'nullable|string|max:255',
        'ORDate' => 'nullable',
        'GeoLocation' => 'nullable|string|max:60',
        'Neighbor1' => 'nullable|string|max:500',
        'Neighbor2' => 'nullable|string|max:500',
        'Notes' => 'nullable|string|max:2000',
        'Status' => 'nullable|string|max:255',
        'DateTimeDownloaded' => 'nullable',
        'DateTimeLinemanArrived' => 'nullable',
        'DateTimeLinemanExecuted' => 'nullable',
        'UserId' => 'nullable|string|max:255',
        'CrewAssigned' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Trash' => 'nullable|string',
        'Office' => 'nullable|string',
        'CurrentMeterNo' => 'nullable|string',
        'CurrentMeterBrand' => 'nullable|string',
        'CurrentMeterReading' => 'nullable|string',
        'KwhRating' => 'nullable|string',
        'PercentError' => 'nullable|string',
        'NewMeterNo' => 'nullable|string',
        'NewMeterBrand' => 'nullable|string',
        'NewMeterReading' => 'nullable|string',
        'ServicePeriod' => 'nullable|string'
    ];

    public static function getAddress($ticket) {
        // if ($ticket->Sitio==null && ($ticket->Barangay!=null && $ticket->Town!=null)) {
        //     return $ticket->Barangay . ', ' . $ticket->Town;
        // } elseif($ticket->Sitio!=null && ($ticket->Barangay!=null && $ticket->Town!=null)) {
        //     return $ticket->Sitio . ', ' . $ticket->Barangay . ', ' . $ticket->Town;
        // }

        return ($ticket->Sitio!=null ? $ticket->Sitio . ',' : '') . '' . ($ticket->Barangay!=null ? $ticket->Barangay . ',' : '') . '' . ($ticket->Town!=null ? $ticket->Town : '');
    }

    public static function getAverageDailyDivisor() {
        return 22;
    }

    public static function getDisconnectionDelinquencyId() {
        return '1668541254423';
    }

    public static function getMeterRelatedComplainsId() {
        return ['1668541254390']; // Change Meter
    }

    public static function getChangeMeter() {
        return '1668541254390';
    }

    public static function getViolations() {
        return ['1668541254425']; // Pilferage
    }

    public static function getReconnection() {
        return '1668541254428';
    }
}
