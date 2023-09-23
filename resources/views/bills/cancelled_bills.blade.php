@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Cancelled Bills Report</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            {{-- HEADER --}}
            <div class="card shadow-none">
                <div class="card-body ">
                    {!! Form::open(['route' => 'bills.cancelled-bills', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Area</label>
                            <select id="Area" name="Area" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Area']) && $_GET['Area']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="From">From</label>
                            <input type="text" class="form-control form-control-sm" id="From" name="From" required placeholder="Select date" value="{{ isset($_GET['From']) ? $_GET['From'] : date('Y-m-d') }}" required>
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#From').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="To">To</label>
                            <input type="text" class="form-control form-control-sm" id="To" name="To" required placeholder="Select date" value="{{ isset($_GET['To']) ? $_GET['To'] : date('Y-m-d') }}" required>
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#To').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            {{-- DATA DISPLAY --}}
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <thead>
                            <th>Account Number</th>
                            <th>Consumer Name</th>
                            <th>Address</th>
                            <th>Billing Month</th>
                            <th>Date Billed</th>
                            <th>Kwh Used</th>
                            <th>Amount Due</th>
                            <th>Requested By</th>
                            <th>Approved By</th>
                            <th>Remarks/Reason</th>
                            <th>Date/Time Cancelled</th>
                        </thead>
                        <tbody>
                            @foreach ($bills as $item)
                                <tr>
                                    <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                    <td>{{ $item->ServicePeriod != null ? date('F Y', strtotime($item->ServicePeriod)) : '-' }}</td>
                                    <td>{{ date('M d, Y', strtotime($item->BillingDate)) }}</td>
                                    <td class="text-right"><strong>{{ $item->KwhUsed }}</strong></td>
                                    <td class="text-right text-danger"><strong>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : '0' }}</strong></td>
                                    <td>{{ $item->Requested }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->Notes }}</td>
                                    <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#print-btn').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('bills/print-cancelled-bills') }}" + "/" + $('#From').val() + "/" + $('#To').val() + "/" + $('#Area').val()
            })
        })
    </script>
@endpush
