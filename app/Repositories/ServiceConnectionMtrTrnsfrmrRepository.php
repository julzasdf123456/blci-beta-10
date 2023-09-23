<?php

namespace App\Repositories;

use App\Models\ServiceConnectionMtrTrnsfrmr;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionMtrTrnsfrmrRepository
 * @package App\Repositories
 * @version August 13, 2021, 1:39 am UTC
*/

class ServiceConnectionMtrTrnsfrmrRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'MeterSerialNumber',
        'MeterBrand',
        'MeterSealNumber',
        'MeterKwhStart',
        'MeterEnclosureType',
        'MeterHeight',
        'MeterNotes',
        'DirectRatedCapacity',
        'InstrumentRatedCapacity',
        'InstrumentRatedLineType',
        'CTPhaseA',
        'CTPhaseB',
        'CTPhaseC',
        'PTPhaseA',
        'PTPhaseB',
        'PTPhaseC',
        'BrandPhaseA',
        'BrandPhaseB',
        'BrandPhaseC',
        'SNPhaseA',
        'SNPhaseB',
        'SNPhaseC',
        'SecuritySealPhaseA',
        'SecuritySealPhaseB',
        'SecuritySealPhaseC',
        'Phase',
        'TransformerQuantity',
        'TransformerRating',
        'TransformerOwnershipType',
        'TransformerOwnership',
        'TransformerBrand'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ServiceConnectionMtrTrnsfrmr::class;
    }
}
