<div class="table-responsive">
    <table class="table" id="billingTransformers-table">
        <thead>
        <tr>
            <th>Serviceaccountid</th>
        <th>Transformernumber</th>
        <th>Rating</th>
        <th>Rentalfee</th>
        <th>Load</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billingTransformers as $billingTransformers)
            <tr>
                <td>{{ $billingTransformers->ServiceAccountId }}</td>
            <td>{{ $billingTransformers->TransformerNumber }}</td>
            <td>{{ $billingTransformers->Rating }}</td>
            <td>{{ $billingTransformers->RentalFee }}</td>
            <td>{{ $billingTransformers->Load }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billingTransformers.destroy', $billingTransformers->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billingTransformers.show', [$billingTransformers->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billingTransformers.edit', [$billingTransformers->id]) }}"
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
