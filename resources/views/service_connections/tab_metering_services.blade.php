<div class="row">
    <div class="col-lg-12 table-responsive">
        <table class="table table-hover table-sm table-bordered">
            <thead>
                <th>Type of Service</th>
                <th>Meter Seal No.</th>
                <th>Lead Seal</th>
                <th>Meter Number</th>
                <th>Multiplier</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Status</th>
                <th>Done By</th>
                <th>Electrician</th>
                <th>Remarks</th>
                <th>Svc. Date</th>
            </thead>
            <tbody>
                @foreach ($metering as $item)
                    <tr>
                        <td>{{ $item->TypeOfService }}</td>
                        <td>{{ $item->MeterSealNumber }}</td>
                        <td>{{ $item->IsLeadSeal }}</td>
                        <td>{{ $item->MeterNumber }}</td>
                        <td>{{ $item->Multiplier }}</td>
                        <td>{{ $item->MeterType }}</td>
                        <td>{{ $item->MeterBrand }}</td>
                        <td>{{ $item->MeterStatus }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->PrivateElectrician }}</td>
                        <td>{{ $item->Notes }}</td>
                        <td>{{ $item->ServiceDate != null ? date('F d, Y h:i A', strtotime($item->ServiceDate)) : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>