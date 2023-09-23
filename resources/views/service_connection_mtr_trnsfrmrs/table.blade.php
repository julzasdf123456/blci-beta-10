<div class="table-responsive">
    <table class="table" id="serviceConnectionMtrTrnsfrmrs-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Meterserialnumber</th>
        <th>Meterbrand</th>
        <th>Metersealnumber</th>
        <th>Meterkwhstart</th>
        <th>Meterenclosuretype</th>
        <th>Meterheight</th>
        <th>Meternotes</th>
        <th>Directratedcapacity</th>
        <th>Instrumentratedcapacity</th>
        <th>Instrumentratedlinetype</th>
        <th>Ctphasea</th>
        <th>Ctphaseb</th>
        <th>Ctphasec</th>
        <th>Ptphasea</th>
        <th>Ptphaseb</th>
        <th>Ptphasec</th>
        <th>Brandphasea</th>
        <th>Brandphaseb</th>
        <th>Brandphasec</th>
        <th>Snphasea</th>
        <th>Snphaseb</th>
        <th>Snphasec</th>
        <th>Securitysealphasea</th>
        <th>Securitysealphaseb</th>
        <th>Securitysealphasec</th>
        <th>Phase</th>
        <th>Transformerquantity</th>
        <th>Transformerrating</th>
        <th>Transformerownershiptype</th>
        <th>Transformerownership</th>
        <th>Transformerbrand</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionMtrTrnsfrmrs as $serviceConnectionMtrTrnsfrmr)
            <tr>
                <td>{{ $serviceConnectionMtrTrnsfrmr->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterSerialNumber }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterBrand }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterSealNumber }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterKwhStart }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterEnclosureType }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterHeight }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->MeterNotes }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->DirectRatedCapacity }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->InstrumentRatedCapacity }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->InstrumentRatedLineType }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->CTPhaseA }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->CTPhaseB }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->CTPhaseC }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->PTPhaseA }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->PTPhaseB }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->PTPhaseC }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->BrandPhaseA }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->BrandPhaseB }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->BrandPhaseC }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->SNPhaseA }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->SNPhaseB }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->SNPhaseC }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->SecuritySealPhaseA }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->SecuritySealPhaseB }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->SecuritySealPhaseC }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->Phase }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->TransformerQuantity }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->TransformerRating }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->TransformerOwnershipType }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->TransformerOwnership }}</td>
            <td>{{ $serviceConnectionMtrTrnsfrmr->TransformerBrand }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionMtrTrnsfrmrs.destroy', $serviceConnectionMtrTrnsfrmr->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionMtrTrnsfrmrs.show', [$serviceConnectionMtrTrnsfrmr->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionMtrTrnsfrmrs.edit', [$serviceConnectionMtrTrnsfrmr->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
