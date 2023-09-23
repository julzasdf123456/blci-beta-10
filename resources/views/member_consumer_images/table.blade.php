<div class="table-responsive">
    <table class="table" id="memberConsumerImages-table">
        <thead>
        <tr>
            <th>Consumerid</th>
        <th>Picturepath</th>
        <th>Heximage</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($memberConsumerImages as $memberConsumerImages)
            <tr>
                <td>{{ $memberConsumerImages->ConsumerId }}</td>
            <td>{{ $memberConsumerImages->PicturePath }}</td>
            <td>{{ $memberConsumerImages->HexImage }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['memberConsumerImages.destroy', $memberConsumerImages->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('memberConsumerImages.show', [$memberConsumerImages->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('memberConsumerImages.edit', [$memberConsumerImages->id]) }}"
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
