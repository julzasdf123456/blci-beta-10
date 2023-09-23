<div class="table-responsive">
    <table class="table" id="signatories-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Office</th>
        <th>Signature</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($signatories as $signatories)
            <tr>
                <td>{{ $signatories->Name }}</td>
            <td>{{ $signatories->Office }}</td>
            <td><img src="{{ $signatories->Signature }}" alt="" style="height: 50px;"></td>
            <td>{{ $signatories->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['signatories.destroy', $signatories->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('signatories.show', [$signatories->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('signatories.edit', [$signatories->id]) }}"
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
