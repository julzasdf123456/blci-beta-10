<div class="table-responsive">
    <table class="table" id="spanningDatas-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Primaryspan</th>
        <th>Primarysize</th>
        <th>Primarytype</th>
        <th>Neutralspan</th>
        <th>Neutralsize</th>
        <th>Neutraltype</th>
        <th>Secondaryspan</th>
        <th>Secondarysize</th>
        <th>Secondarytype</th>
        <th>Sdwspan</th>
        <th>Sdwsize</th>
        <th>Sdwtype</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($spanningDatas as $spanningData)
            <tr>
                <td>{{ $spanningData->ServiceConnectionId }}</td>
            <td>{{ $spanningData->PrimarySpan }}</td>
            <td>{{ $spanningData->PrimarySize }}</td>
            <td>{{ $spanningData->PrimaryType }}</td>
            <td>{{ $spanningData->NeutralSpan }}</td>
            <td>{{ $spanningData->NeutralSize }}</td>
            <td>{{ $spanningData->NeutralType }}</td>
            <td>{{ $spanningData->SecondarySpan }}</td>
            <td>{{ $spanningData->SecondarySize }}</td>
            <td>{{ $spanningData->SecondaryType }}</td>
            <td>{{ $spanningData->SDWSpan }}</td>
            <td>{{ $spanningData->SDWSize }}</td>
            <td>{{ $spanningData->SDWType }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['spanningDatas.destroy', $spanningData->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('spanningDatas.show', [$spanningData->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('spanningDatas.edit', [$spanningData->id]) }}"
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
