@php
    use App\Models\ORAssigning;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 col-lg-2 col-md-3">
                    <h4>OR Maintenance</h4>
                </div>
                <div class="col-sm-12 col-lg-10 col-md-9">
                    {!! Form::open(['route' => 'transactionIndices.or-maintenance', 'method' => 'GET']) !!}
                    <button type="submit" class="btn btn-sm btn-primary float-right">Filter</button>
                    <input type="text" class="form-control form-control-sm float-right" id="Date" name="Date" style="width: 160px; margin-right: 10px;" value="{{ date('Y-m-d') }}">
                    <label for="Date" class="float-right" style="margin-right: 10px;">Set OR Date</label>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#Date').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#power-bills" data-toggle="tab">
                            <i class="fas fa-file-invoice"></i>
                            Power Bills</a></li>
                        <li class="nav-item"><a class="nav-link" href="#non-power-bills" data-toggle="tab">
                            <i class="fas fa-unlink"></i>
                            Non-Power Bills</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="power-bills">
                            <span><i>Press <strong>F3</strong> to search</i></span>
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>OR Number</th>
                                    <th>Account Number</th>
                                    <th>Service Account Name</th>
                                    <th>Billing Month</th>
                                    <th>Net Amount</th>
                                    <th>Surcharges</th>
                                    <th>Total Amount Paid</th>
                                </thead>
                                <tbody>
                                    @foreach ($paidBills as $item)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->ORNumber }}" onkeydown="updateOR(this, '{{ $item->id }}', 'POWERBILL')">
                                            </td>
                                            <td>{{ $item->OldAccountNo }}</td>
                                            <td>{{ $item->ServiceAccountName }}</td>
                                            <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                            <td>{{ number_format($item->NetAmount, 2) }}</td>
                                            <td>{{ number_format($item->Surcharge, 2) }}</td>
                                            <td>{{ number_format(floatval($item->NetAmount) + floatval($item->Surcharge), 2) }}</td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="non-power-bills">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>OR Number</th>
                                    <th>Payment Title</th>
                                    <th>Payment Details</th>
                                    <th>Payment Source</th>
                                    <th>Payee Name</th>
                                    <th>Amount Paid</th>
                                </thead>
                                <tbody>
                                    @foreach ($nonPowerBills as $item)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->ORNumber }}" onkeydown="updateOR(this, '{{ $item->id }}', 'NONPOWERBILL')">
                                            </td>
                                            <td>{{ $item->PaymentTitle }}</td>
                                            <td>{{ $item->PaymentDetails }}</td>
                                            <td>{{ $item->Source }}</td>
                                            <td>{{ $item->PayeeName }}</td>
                                            <td>{{ number_format($item->Total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('page_scripts')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })

        function updateOR(element, id, type) {
            if (event.key == 'Enter') {
                $.ajax({
                    url : "{{ route('transactionIndices.update-or-number') }}",
                    type : 'GET',
                    data : {
                        Type : type,
                        id : id,
                        ORNumber : element.value,
                    },
                    success : function(res) {
                        if (res['res'] == 'ok') {
                            Toast.fire({
                                icon: 'success',
                                title: res['message']
                            })
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: res['message']
                            })
                        }                        
                    },
                    error : function(err) {
                        Swal.fire({
                            title : 'Oops!',
                            text : 'An error occurred while attempting to change the OR Number',
                            icon : 'error'
                        })
                    }
                })
            }
        }
    </script>
@endpush