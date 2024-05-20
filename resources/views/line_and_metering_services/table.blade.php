<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="line-and-metering-services-table">
            <thead>
            <tr>
                <th>Serviceconnectionid</th>
                <th>Typeofservice</th>
                <th>Metersealnumber</th>
                <th>Isleadseal</th>
                <th>Meterstatus</th>
                <th>Meternumber</th>
                <th>Multiplier</th>
                <th>Metertype</th>
                <th>Meterbrand</th>
                <th>Notes</th>
                <th>Servicedate</th>
                <th>Userid</th>
                <th>Privateelectrician</th>
                <th>Linelength</th>
                <th>Conductortype</th>
                <th>Conductorsize</th>
                <th>Conductorunit</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lineAndMeteringServices as $lineAndMeteringServices)
                <tr>
                    <td>{{ $lineAndMeteringServices->ServiceConnectionId }}</td>
                    <td>{{ $lineAndMeteringServices->TypeOfService }}</td>
                    <td>{{ $lineAndMeteringServices->MeterSealNumber }}</td>
                    <td>{{ $lineAndMeteringServices->IsLeadSeal }}</td>
                    <td>{{ $lineAndMeteringServices->MeterStatus }}</td>
                    <td>{{ $lineAndMeteringServices->MeterNumber }}</td>
                    <td>{{ $lineAndMeteringServices->Multiplier }}</td>
                    <td>{{ $lineAndMeteringServices->MeterType }}</td>
                    <td>{{ $lineAndMeteringServices->MeterBrand }}</td>
                    <td>{{ $lineAndMeteringServices->Notes }}</td>
                    <td>{{ $lineAndMeteringServices->ServiceDate }}</td>
                    <td>{{ $lineAndMeteringServices->UserId }}</td>
                    <td>{{ $lineAndMeteringServices->PrivateElectrician }}</td>
                    <td>{{ $lineAndMeteringServices->LineLength }}</td>
                    <td>{{ $lineAndMeteringServices->ConductorType }}</td>
                    <td>{{ $lineAndMeteringServices->ConductorSize }}</td>
                    <td>{{ $lineAndMeteringServices->ConductorUnit }}</td>
                    <td>{{ $lineAndMeteringServices->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['lineAndMeteringServices.destroy', $lineAndMeteringServices->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('lineAndMeteringServices.show', [$lineAndMeteringServices->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('lineAndMeteringServices.edit', [$lineAndMeteringServices->id]) }}"
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

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $lineAndMeteringServices])
        </div>
    </div>
</div>
