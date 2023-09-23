@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Third-Party Collection Validation</h4>
            </div>

            <div class="col-sm-6">
                <button class="btn btn-danger float-right" id="delete-all"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card" style="height: 75vh;">
            <div class="card-header">
                <span class="card-title">Uploaded Collection Summary</span>

                <div class="card-tools"> 
                    <span style="border-left: 20px solid #9c27b0; padding-left: 10px; margin-right: 20px; font-size: .9em;">Amount Mismatch</span>
                    <span style="border-left: 20px solid #ff7043; padding-left: 10px; margin-right: 20px; font-size: .9em;">Double Payment</span>
                    <span style="border-left: 20px solid #1de9b6; padding-left: 10px; margin-right: 20px; font-size: .9em;">Ready for Posting</span>
                    <span style="border-left: 20px solid #ffcdd2; padding-left: 10px; margin-right: 20px; font-size: .9em;">No Bill Number</span>
                    <span style="border-left: 20px solid #e53935; padding-left: 10px; margin-right: 20px; font-size: .9em;">Account Not Found</span>
                </div>
            </div>
            
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed text-nowrap table-borderd table-sm">
                    <thead>
                        <th style="width: 25px;"></th>
                        <th>Reference No.</th>
                        <th>Bill No.</th>
                        <th>Account No.</th>
                        <th>Account Name</th>
                        <th>Billing Mo.</th>
                        <th class="text-right">OR Number</th>
                        <th>OR Date</th>
                        <th>Company</th>
                        <th>Teller</th>
                        <th class="text-right">Bill Amnt.</th>
                        <th class="text-right">Amount Paid</th>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                            $doublePayments = false;
                            $postingEnabled = false;
                        @endphp
                        @foreach ($paidBills as $item)
                            @if ($item->OldAccountNo != null)
                                {{-- IF AN ACCOUNT IS FOUND --}}
                                @if ($item->BillNumber == null)
                                    {{-- IF NO BILL FOUND --}}
                                    <tr style="background: #ffcdd2;">
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->DCRNumber }}</td>
                                        <td>{{ $item->BillNumber }}</td>
                                        <th><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></th>
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td class="text-right">{{ $item->ORNumber }}</td>
                                        <td>{{ $item->ORDate }}</td >
                                        <td>{{ $item->ObjectSourceId }}</td> {{-- COMPANY --}}
                                        <td>{{ $item->CheckNo }}</td> {{-- TELLER --}}
                                        <th class="text-right">{{ $item->BillAmount != null ? number_format($item->BillAmount, 2) : '' }}</th> 
                                        <th class="text-right">{{ number_format($item->NetAmount, 2) }}</th> 
                                    </tr>
                                @else
                                    {{-- IF BILL IS FOUND --}}
                                    @if ($item->Duplicates > 0)
                                        {{-- IF DOUBLE PAYMENT --}}
                                        <tr style="background: #ff7043; color: white;">
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $item->DCRNumber }}</td>
                                            <td>{{ $item->BillNumber }}</td>
                                            <th><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></th>
                                            <td>{{ $item->ServiceAccountName }}</td>
                                            <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                                            <td class="text-right">{{ $item->ORNumber }}</td>
                                            <td>{{ $item->ORDate }}</td>
                                            <td>{{ $item->ObjectSourceId }}</td> {{-- COMPANY --}}
                                            <td>{{ $item->CheckNo }}</td> {{-- TELLER --}}
                                            <th class="text-right">{{ $item->BillAmount != null ? number_format($item->BillAmount, 2) : '' }}</th> 
                                            <th class="text-right">{{ number_format($item->NetAmount, 2) }}</th> 
                                        </tr>
                                        @php
                                            $doublePayments = true;
                                        @endphp
                                    @else
                                        {{-- IF AMOUNT DOESNT MATCH --}}
                                        @if (round($item->BillAmount, 2) != round($item->NetAmount, 2))
                                            {{-- IF GO FOR POSTING --}}
                                            <tr style="background: #9c27b0">
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->DCRNumber }}</td>
                                                <td>{{ $item->BillNumber }}</td>
                                                <th><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></th>
                                                <td>{{ $item->ServiceAccountName }}</td>
                                                <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                                                <td class="text-right">{{ $item->ORNumber }}</td>
                                                <td>{{ $item->ORDate }}</td>
                                                <td>{{ $item->ObjectSourceId }}</td> {{-- COMPANY --}}
                                                <td>{{ $item->CheckNo }}</td> {{-- TELLER --}}
                                                <th class="text-right">{{ $item->BillAmount != null ? number_format($item->BillAmount, 2) : '' }}</th> 
                                                <th class="text-right">{{ number_format($item->NetAmount, 2) }}</th> 
                                            </tr>
                                            {{-- @php
                                                $postingEnabled = false;
                                            @endphp  --}}
                                        @else
                                            {{-- IF GO FOR POSTING --}}
                                            <tr style="background: #1de9b6">
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->DCRNumber }}</td>
                                                <td>{{ $item->BillNumber }}</td>
                                                <th><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></th>
                                                <td>{{ $item->ServiceAccountName }}</td>
                                                <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                                                <td class="text-right">{{ $item->ORNumber }}</td>
                                                <td>{{ $item->ORDate }}</td>
                                                <td>{{ $item->ObjectSourceId }}</td> {{-- COMPANY --}}
                                                <td>{{ $item->CheckNo }}</td> {{-- TELLER --}}
                                                <th class="text-right">{{ $item->BillAmount != null ? number_format($item->BillAmount, 2) : '' }}</th> 
                                                <th class="text-right">{{ number_format($item->NetAmount, 2) }}</th> 
                                            </tr>
                                            @php
                                                $postingEnabled = true;
                                            @endphp 
                                        @endif
                                    @endif                                    
                                @endif
                            @else
                            {{-- IF NO ACCOUNT FOUND --}}
                                <tr class="bg-danger">
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $item->DCRNumber }}</td>
                                    <td>{{ $item->BillNumber }}</td>
                                    <th>*** {{ $item->AuditedBy }}</th>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                                    <td class="text-right">{{ $item->ORNumber }}</td>
                                    <td>{{ $item->ORDate }}</td>
                                    <td>{{ $item->ObjectSourceId }}</td> {{-- COMPANY --}}
                                    <td>{{ $item->CheckNo }}</td> {{-- TELLER --}}
                                    <th class="text-right">{{ $item->BillAmount != null ? number_format($item->BillAmount, 2) : '' }}</th> 
                                    <th class="text-right">{{ number_format($item->NetAmount, 2) }}</th> 
                                </tr>
                            @endif
                            
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="{{ route('paidBills.third-party-collection') }}" class="btn btn-sm btn-default"><i class="fas fa-check ico-tab-mini"></i>Done</a>
                @if ($postingEnabled)
                    <a href="{{ route('paidBills.post-payments', [$seriesNo]) }}" class="btn btn-sm btn-success float-right"><i class="fas fa-check-circle ico-tab-mini"></i> Post Payments</a>
                @endif
                
                @if ($doublePayments==true)
                    <a href="{{ route('paidBills.deposit-double-payments', [$seriesNo]) }}" class="btn btn-sm btn-default float-right ico-tab-mini"><i class="fas fa-piggy-bank ico-tab-mini"></i> Deposit Double Payments</a>
                @endif                
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#delete-all').on('click', function() {
                Swal.fire({
                    title: 'Area you sure you want to clear this upload?',
                    showDenyButton: true,
                    confirmButtonText: 'No',
                    denyButtonText: `Yes`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        
                    } else if (result.isDenied) {
                        // DELETE ALL
                        $.ajax({
                            url : "{{ route('paidBills.clear-all-tcp-uploads') }}",
                            type : 'GET',
                            data : {
                                SeriesNo : "{{ $seriesNo }}",
                            },
                            success : function(res) {
                                Swal.fire({
                                    title : 'Uploads cleared!',
                                    icon : 'success',
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                window.location.href = "{{ url(route('paidBills.third-party-collection')) }}"
                            },
                            error : function(err) {
                                Swal.fire({
                                    title : 'Error deleting uploads!',
                                    icon : 'error'
                                })
                            }
                        })
                    }
                })
            })
        })
    </script>
@endpush