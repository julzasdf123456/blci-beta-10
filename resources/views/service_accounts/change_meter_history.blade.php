<div class="modal fade" id="modal-meter-logs" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Meter Logs</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Brand</th>
                        <th>Serial Number</th>
                        <th>Seal Number</th>
                        <th>Multiplier</th>
                        <th>Connection Date</th>
                        <th>Initial Reading</th>
                        <th>Last Reading</th>
                    </thead>
                    <tbody>
                        @foreach ($meterHistory as $item)
                            <tr>
                                <td>{{ $item->Brand }}</td>
                                <td>{{ $item->SerialNumber }}</td>
                                <td>{{ $item->SealNumber }}</td>
                                <td>{{ $item->Multiplier }}</td>
                                <td>{{ $item->ConnectionDate }}</td>
                                <td>{{ $item->InitialReading }}</td>
                                <td>{{ $item->LatestReading }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>