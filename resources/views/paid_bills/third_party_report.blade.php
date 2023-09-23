@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<p style="padding-top: 8px;"><i class="fas fa-file ico-tab"></i>Third Party Reports</strong></p>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body">
                {!! Form::open(['route' => 'paidBills.third-party-report', 'method' => 'GET']) !!}
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="">Town</label>
                        <select id="Town" name="Town" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Day</label>
                        <input type="text" name="Day" id="Day" class="form-control form-control-sm" placeholder="Select Day" value="{{ isset($_GET['Day']) ? $_GET['Day'] : '' }}">
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#Day').datetimepicker({
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
    </div>

    {{-- DATA --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 65vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th class="text-center">Account No.</th>
                        <th class="text-center">Account Name</th>
                        <th class="text-center">Account Address</th>
                        <th class="text-center">Amount Paid</th>
                        <th class="text-center">Collection <br> Partner</th>
                        <th class="text-center">OR Number</th>
                        <th class="text-center">OR Date</th>
                        <th class="text-center">Teller</th>
                        <th class="text-center">Posted By</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->OldAccountNo }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td class="text-right">{{ number_format($item->NetAmount, 2) }}</td>
                                <td>{{ $item->ObjectSourceId }}</td>
                                <td>{{ $item->ORNumber }}</td>
                                <td>{{ date('M d, Y', strtotime($item->ORDate)) }}</td>
                                <td>{{ $item->CheckNo }}</td>
                                <td>{{ $item->name }}</td>
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
                if (jQuery.isEmptyObject($('#Day').val())) {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Fill in the date to print'
                    })
                } else {
                    window.location.href = "{{ url('/paid_bills/print-third-party-report') }}" + "/" + $('#Day').val() + "/" + $('#Town').val()
                }
            })
        })
    </script>
@endpush