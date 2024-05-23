<div class="row">
    <div class="col-lg-12 table-responsive">
        <table class="table table-hover table-sm table-bordered">
            <thead>
                <th>Length</th>
                <th>Conductor</th>
                <th>Value</th>
                <th>Unit</th>
                <th>Done By</th>
                <th>Svc. Date</th>
            </thead>
            <tbody>
                @foreach ($line as $item)
                    <tr>
                        <td>{{ $item->LineLength }}</td>
                        <td>{{ $item->ConductorType }}</td>
                        <td>{{ $item->ConductorSize }}</td>
                        <td>{{ $item->ConductorUnit }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->ServiceDate != null ? date('F d, Y h:i A', strtotime($item->ServiceDate)) : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>