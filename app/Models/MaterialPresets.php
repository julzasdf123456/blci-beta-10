<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPresets extends Model
{
    public $table = 'CRM_MaterialPresets';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'MeterBaseSocket',
        'MetalboxTypeA',
        'MetalboxTypeB',
        'Pipe',
        'EntranceCap',
        'Adapter',
        'Locknot',
        'Mailbox',
        'Buckle',
        'PvcElbow',
        'StainlessStrap',
        'Plyboard',
        'StrainInsulator',
        'StraindedWireEight',
        'StrandedWireSix',
        'TwistedWireSix',
        'TwistedWireFour'
    ];

    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'MeterBaseSocket' => 'string',
        'MetalboxTypeA' => 'string',
        'MetalboxTypeB' => 'string',
        'Pipe' => 'string',
        'EntranceCap' => 'string',
        'Adapter' => 'string',
        'Locknot' => 'string',
        'Mailbox' => 'string',
        'Buckle' => 'string',
        'PvcElbow' => 'string',
        'StainlessStrap' => 'string',
        'Plyboard' => 'string',
        'StrainInsulator' => 'string',
        'StraindedWireEight' => 'string',
        'StrandedWireSix' => 'string',
        'TwistedWireSix' => 'string',
        'TwistedWireFour' => 'string'
    ];

    public static array $rules = [
        'ServiceConnectionId' => 'nullable|string|max:50',
        'MeterBaseSocket' => 'nullable|string|max:50',
        'MetalboxTypeA' => 'nullable|string|max:50',
        'MetalboxTypeB' => 'nullable|string|max:50',
        'Pipe' => 'nullable|string|max:50',
        'EntranceCap' => 'nullable|string|max:50',
        'Adapter' => 'nullable|string|max:50',
        'Locknot' => 'nullable|string|max:50',
        'Mailbox' => 'nullable|string|max:50',
        'Buckle' => 'nullable|string|max:50',
        'PvcElbow' => 'nullable|string|max:50',
        'StainlessStrap' => 'nullable|string|max:50',
        'Plyboard' => 'nullable|string|max:50',
        'StrainInsulator' => 'nullable|string|max:50',
        'StraindedWireEight' => 'nullable|string|max:50',
        'StrandedWireSix' => 'nullable|string|max:50',
        'TwistedWireSix' => 'nullable|string|max:50',
        'TwistedWireFour' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
