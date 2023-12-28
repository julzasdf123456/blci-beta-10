<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="sms-settings-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Bills</th>
                <th>Noticeofdisconnection</th>
                <th>Serviceconnectionreception</th>
                <th>Inspectioncreation</th>
                <th>Paymentapproved</th>
                <th>Inspectiontoday</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($smsSettings as $smsSettings)
                <tr>
                    <td>{{ $smsSettings->id }}</td>
                    <td>{{ $smsSettings->Bills }}</td>
                    <td>{{ $smsSettings->NoticeOfDisconnection }}</td>
                    <td>{{ $smsSettings->ServiceConnectionReception }}</td>
                    <td>{{ $smsSettings->InspectionCreation }}</td>
                    <td>{{ $smsSettings->PaymentApproved }}</td>
                    <td>{{ $smsSettings->InspectionToday }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['smsSettings.destroy', $smsSettings->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('smsSettings.show', [$smsSettings->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('smsSettings.edit', [$smsSettings->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $smsSettings])
        </div>
    </div>
</div>
