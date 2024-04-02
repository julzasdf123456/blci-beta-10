<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="material-presets-table">
            <thead>
            <tr>
                <th>Serviceconnectionid</th>
                <th>Meterbasesocket</th>
                <th>Metalboxtypea</th>
                <th>Metalboxtypeb</th>
                <th>Pipe</th>
                <th>Entrancecap</th>
                <th>Adapter</th>
                <th>Locknot</th>
                <th>Mailbox</th>
                <th>Buckle</th>
                <th>Pvcelbow</th>
                <th>Stainlessstrap</th>
                <th>Plyboard</th>
                <th>Straininsulator</th>
                <th>Straindedwireeight</th>
                <th>Strandedwiresix</th>
                <th>Twistedwiresix</th>
                <th>Twistedwirefour</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($materialPresets as $materialPresets)
                <tr>
                    <td>{{ $materialPresets->ServiceConnectionId }}</td>
                    <td>{{ $materialPresets->MeterBaseSocket }}</td>
                    <td>{{ $materialPresets->MetalboxTypeA }}</td>
                    <td>{{ $materialPresets->MetalboxTypeB }}</td>
                    <td>{{ $materialPresets->Pipe }}</td>
                    <td>{{ $materialPresets->EntranceCap }}</td>
                    <td>{{ $materialPresets->Adapter }}</td>
                    <td>{{ $materialPresets->Locknot }}</td>
                    <td>{{ $materialPresets->Mailbox }}</td>
                    <td>{{ $materialPresets->Buckle }}</td>
                    <td>{{ $materialPresets->PvcElbow }}</td>
                    <td>{{ $materialPresets->StainlessStrap }}</td>
                    <td>{{ $materialPresets->Plyboard }}</td>
                    <td>{{ $materialPresets->StrainInsulator }}</td>
                    <td>{{ $materialPresets->StraindedWireEight }}</td>
                    <td>{{ $materialPresets->StrandedWireSix }}</td>
                    <td>{{ $materialPresets->TwistedWireSix }}</td>
                    <td>{{ $materialPresets->TwistedWireFour }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['materialPresets.destroy', $materialPresets->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('materialPresets.show', [$materialPresets->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('materialPresets.edit', [$materialPresets->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $materialPresets])
        </div>
    </div>
</div>
