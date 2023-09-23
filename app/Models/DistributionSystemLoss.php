<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DistributionSystemLoss
 * @package App\Models
 * @version April 16, 2022, 2:46 pm PST
 *
 * @property string $ServicePeriod
 * @property string $VictoriasSubstation
 * @property string $SagaySubstation
 * @property string $SanCarlosSubstation
 * @property string $EscalanteSubstation
 * @property string $LopezSubstation
 * @property string $CadizSubstation
 * @property string $IpiSubstation
 * @property string $TobosoCalatravaSubstation
 * @property string $VictoriasMillingCompany
 * @property string $SanCarlosBionergy
 * @property string $TotalEnergyInput
 * @property string $EnergySales
 * @property string $EnergyAdjustmentRecoveries
 * @property string $TotalEnergyOutput
 * @property string $TotalSystemLoss
 * @property string $TotalSystemLossPercentage
 * @property string $UserId
 * @property string $From
 * @property string $To
 * @property string $Status
 * @property string $Notes
 */
class DistributionSystemLoss extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Reports_DistributionSystemLoss';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServicePeriod',
        'VictoriasSubstation',
        'SagaySubstation',
        'SanCarlosSubstation',
        'EscalanteSubstation',
        'LopezSubstation',
        'CadizSubstation',
        'IpiSubstation',
        'TobosoCalatravaSubstation',
        'VictoriasMillingCompany',
        'SanCarlosBionergy',
        'TotalEnergyInput',
        'EnergySales',
        'EnergyAdjustmentRecoveries',
        'TotalEnergyOutput',
        'TotalSystemLoss',
        'TotalSystemLossPercentage',
        'UserId',
        'From',
        'To',
        'Status',
        'Notes',
        'CalatravaSubstation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServicePeriod' => 'date',
        'VictoriasSubstation' => 'string',
        'SagaySubstation' => 'string',
        'SanCarlosSubstation' => 'string',
        'EscalanteSubstation' => 'string',
        'LopezSubstation' => 'string',
        'CadizSubstation' => 'string',
        'IpiSubstation' => 'string',
        'TobosoCalatravaSubstation' => 'string',
        'VictoriasMillingCompany' => 'string',
        'SanCarlosBionergy' => 'string',
        'TotalEnergyInput' => 'string',
        'EnergySales' => 'string',
        'EnergyAdjustmentRecoveries' => 'string',
        'TotalEnergyOutput' => 'string',
        'TotalSystemLoss' => 'string',
        'TotalSystemLossPercentage' => 'string',
        'UserId' => 'string',
        'From' => 'date',
        'To' => 'date',
        'Status' => 'string',
        'Notes' => 'string',
        'CalatravaSubstation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServicePeriod' => 'nullable',
        'VictoriasSubstation' => 'nullable|string|max:255',
        'SagaySubstation' => 'nullable|string|max:255',
        'SanCarlosSubstation' => 'nullable|string|max:255',
        'EscalanteSubstation' => 'nullable|string|max:255',
        'LopezSubstation' => 'nullable|string|max:255',
        'CadizSubstation' => 'nullable|string|max:255',
        'IpiSubstation' => 'nullable|string|max:255',
        'TobosoCalatravaSubstation' => 'nullable|string|max:255',
        'VictoriasMillingCompany' => 'nullable|string|max:255',
        'SanCarlosBionergy' => 'nullable|string|max:255',
        'TotalEnergyInput' => 'nullable|string|max:255',
        'EnergySales' => 'nullable|string|max:255',
        'EnergyAdjustmentRecoveries' => 'nullable|string|max:255',
        'TotalEnergyOutput' => 'nullable|string|max:255',
        'TotalSystemLoss' => 'nullable|string|max:255',
        'TotalSystemLossPercentage' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'From' => 'nullable',
        'To' => 'nullable',
        'Status' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'CalatravaSubstation' => 'nullable|string'
    ];

    
}
