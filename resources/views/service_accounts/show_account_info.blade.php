@php
    use App\Models\ServiceAccounts;
@endphp
<div class="card card-outline shadow-none {{ $serviceAccounts->AccountStatus=='ACTIVE' ? 'card-success' : 'card-danger' }}" title="{{ $serviceAccounts->AccountStatus=='ACTIVE' ? 'Account Active' : 'Account Disconnected' }}"">
    <div class="card-header border-0">
        <span class="card-title">Account Details</span>

        <div class="card-tools">
            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                <button class="btn btn-tool" id="change-name" data-toggle="modal" data-target="#modal-change-name" title="Change Name"><i class="fas fa-pen"></i></button>
            @endif
            <button class="btn btn-tool" data-toggle="modal" data-target="#modal-change-name-history" title="Change Name History"><i class="fas fa-history"></i></button>
            <button class="btn btn-tool" data-toggle="modal" data-target="#modal-relocation-history" title="Location History (Relocations)"><i class="fas fa-map"></i></button>
            <button class="btn btn-tool" data-toggle="modal" data-target="#modal-view-map" title="View in map"><i class="fas fa-map-marker-alt"></i></button>
        </div>
    </div>
    <div class="card-body table-responsive px-0">
        <table class="table table-hover table-sm">
            <thead></thead>
            <tbody>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>
                        <strong class="badge {{ $serviceAccounts->AccountStatus=='ACTIVE' ? 'badge-success' : 'badge-danger' }}">{{ $serviceAccounts->AccountStatus }}</strong>
                        @if ($serviceAccounts->DownloadedByDisco=='Yes')
                            <strong class="badge bg-warning">Downloaded by Disco</strong>
                            <button id="remove-download-tag" class="btn btn-xs text-danger" title="Remove Downloaded By Disco Tag"><i class="fas fa-times-circle"></i></button>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Account Number</td>
                    <td><strong>{{ $serviceAccounts->OldAccountNo }}</strong> <span class="text-muted">(Acct ID: {{ $serviceAccounts->id }})</span></td>
                </tr>
                <tr>
                    <td class="text-muted">Account Address</td>
                    <th>{{ ServiceAccounts::getAddress($serviceAccounts) }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Contact Number</td>
                    <th>{{ $serviceAccounts->ContactNumber }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Consumer Type</td>
                    <th>{{ $serviceAccounts->AccountType }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Connection Date</td>
                    <th>{{ $serviceAccounts->ConnectionDate==null ? '-' : date('F d, Y', strtotime($serviceAccounts->ConnectionDate)) }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Area Code</td>
                    <th>{{ $serviceAccounts->AreaCode }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Sequence No.</td>
                    <th>{{ $serviceAccounts->SequenceCode }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Reading Day</td>
                    <th>{{ $serviceAccounts->GroupCode }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Meter Reader</td>
                    <th>{{ $serviceAccounts->MeterReader }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Senior Citizen</td>
                    <th class="{{ $serviceAccounts->SeniorCitizen=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->SeniorCitizen=='Yes' ? 'Yes' : 'No' }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Lifeliner</td>
                    <th class="{{ $serviceAccounts->Lifeliner=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->Lifeliner=='Yes' ? 'Yes' : 'No' }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Contestable</td>
                    <th class="{{ $serviceAccounts->Contestable=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->Contestable=='Yes' ? 'Yes' : 'No' }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Net Metered</td>
                    <th class="{{ $serviceAccounts->NetMetered=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->NetMetered=='Yes' ? 'Yes' : 'No' }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Longevity</td>
                    <th>{{ $serviceAccounts->AccountRetention }}</th>
                </tr>
                @if ($serviceAccounts->AccountRetention != null && $serviceAccounts->AccountRetention == 'Temporary')
                    <tr>
                        <td class="text-muted">Duration (in months)</td>
                        <th>{{ $serviceAccounts->DurationInMonths }}</th>
                    </tr>
                    <tr>
                        <td class="{{ date('Y-m-d', strtotime($serviceAccounts->AccountExpiration)) < date('Y-m-d') ? 'text-danger' : 'text-muted' }}">Expiration</td>
                        <th class="{{ date('Y-m-d', strtotime($serviceAccounts->AccountExpiration)) < date('Y-m-d') ? 'text-danger' : 'text-muted' }}">{{ $serviceAccounts->AccountExpiration != null ? date('F d, Y', strtotime($serviceAccounts->AccountExpiration)) : '-' }}</th>
                    </tr>
                @endif
                {{-- <tr>
                    <td class="text-muted">EVAT 5%</td>
                    <th class="{{ $serviceAccounts->Evat5Percent=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->Evat5Percent=='Yes' ? 'Yes' : 'No' }}</th>
                </tr>
                <tr>
                    <td class="text-muted">EWT 2%</td>
                    <th class="{{ $serviceAccounts->Ewt2Percent=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->Ewt2Percent=='Yes' ? 'Yes' : 'No' }}</th>
                </tr> --}}
                <tr>
                    <td class="text-muted">Coop Consumption</td>
                    <th class="{{ $serviceAccounts->CoopConsumption=='Yes' ? 'text-success' : 'text-muted' }}">{{ $serviceAccounts->CoopConsumption=='Yes' ? 'Yes' : 'No' }}</th>
                </tr>
                <tr>
                    <th>Multiplier</th>
                    <th>{{ $meters != null ? $meters->Multiplier : "1" }}</th>
                </tr>
                {{-- <tr>
                    <th>Coreloss</th>
                    <th>{{ $serviceAccounts->Coreloss }}</th>
                </tr> --}}
                @if ($katas != null)
                    <tr>
                        <th>Katas Ng VAT</th>
                        <th class="text-danger">P {{ number_format($katas->Balance, 2) }}</th>
                    </tr>
                @endif
            </tbody>            
        </table>
    </div>
</div>

{{-- CHANGE NAME --}}
<div class="modal fade" id="modal-change-name" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Name</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="From">From</label>
                    <input type="text" name="From" id="From" value="{{ $serviceAccounts->ServiceAccountName }}" class="form-control" readonly>

                    <label for="To">To</label>
                    <input type="text" name="To" id="To" class="form-control" autofocus>

                    <label for="ChangeNameNotes">Notes</label>
                    <textarea type="text" name="ChangeNameNotes" id="ChangeNameNotes" placeholder="Notes/Remarks" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="change-name-proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>

{{-- CHANGE NAME HISTORY --}}
<div class="modal fade" id="modal-change-name-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Previous Account Names</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Account Names</th>
                        <th>Remarks</th>
                        <th>Changed By</th>
                        <th>Changed On</th>
                    </thead>
                    <tbody>
                        @foreach ($changeNameHistory as $item)
                            <tr>
                                <td>{{ $item->OldAccountName }}</td>
                                <td>{{ $item->Notes }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ date('F d, Y, h:i:s A', strtotime($item->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- RELOCATION HISTORY --}}
<div class="modal fade" id="modal-relocation-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Previous Account Addresses</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Address</th>
                        <th>Area Code</th>
                        <th>Sequence</th>
                        <th>Relocation Date</th>
                    </thead>
                    <tbody>
                        @foreach ($relocationHistory as $item)
                            <tr>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td>{{ $item->AreaCode }}</td>
                                <td>{{ $item->SequenceCode }}</td>
                                <td>{{ date('F d, Y', strtotime($item->RelocationDate)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('service_accounts.map_modal')

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#change-name-proceed').on('click', function() {
                changeName()
            })

            $('#remove-download-tag').on('click', function() {
                Swal.fire({
                    title: 'Confirm Remove Tag',
                    text : 'Remove downloaded by disco tag?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        removeDownloadTag()
                    }
                })
            })
        })   
        
        function changeName() {
            if (jQuery.isEmptyObject($('#To').val())) {
                Swal.fire({
                    title : 'Empty Name',
                    text : 'Provide a new name to continue',
                    icon : 'error'
                })
            } else {
                $.ajax({
                    url : "{{ route('serviceAccounts.change-name') }}",
                    type : 'GET',
                    data : {
                        id : '{{ $serviceAccounts->id }}',
                        NewName : $('#To').val(),
                        Notes : $('#ChangeNameNotes').val()
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            title : 'Oops',
                            text : 'An error occurred while trying to change the name',
                            icon : 'error'
                        })
                    }
                })
            }
            
        }

        function removeDownloadTag() {
            $.ajax({
                url : "{{ route('serviceAccounts.remove-download-tag') }}",
                type : "GET",
                data : {
                    id : '{{ $serviceAccounts->id }}',
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Tag removed'
                    })
                    location.reload()
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error removing tag'
                    })
                }
            })
        }
    </script>
@endpush