<div class="table-responsive">
    <table class="table" id="serviceConnectionPayParticulars-table">
        <thead>
        <tr>
            <th>Particular</th>
            <th>Default Amount</th>
            {{-- <th>VAT Percentage</th> --}}
            <th>Account Number</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionPayParticulars as $serviceConnectionPayParticulars)
            <tr>
                <td>{{ $serviceConnectionPayParticulars->Particular }}</td>
            <td>{{ $serviceConnectionPayParticulars->DefaultAmount }}</td>
            {{-- <td>{{ $serviceConnectionPayParticulars->VatPercentage }}</td> --}}
            <td>{{ $serviceConnectionPayParticulars->AccountNumber }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionPayParticulars.destroy', $serviceConnectionPayParticulars->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionPayParticulars.show', [$serviceConnectionPayParticulars->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionPayParticulars.edit', [$serviceConnectionPayParticulars->id]) }}"
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
