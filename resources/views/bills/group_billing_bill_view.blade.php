@php
    use App\Models\ServiceAccounts;
    use App\Models\MemberConsumers;
    use App\Models\Bills;
@endphp

@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <br>
        <div class="card">
            <div class="card-body">
                <span>
                    <a href="{{ route('bills.print-group-billing', [$memberConsumer->ConsumerId, $servicePeriod, 'No']) }}" class="float-right"><i class="fas fa-print text-warning"></i></a>
                    <h4 style="display: inline; margin-right: 15px;">{{ $memberConsumer != null ? (MemberConsumers::serializeMemberName($memberConsumer)) : '' }}</h4>
                    <p class="text-muted">Billing Month: {{ date('F, Y', strtotime($servicePeriod)) }}</p>
                </span>

                <div class="divider"></div>
                
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Account ID:</th>
                        <th>Account No:</th>
                        <th>Consumer Name</th>
                        <th>Bill No</th>
                        <th>Consumer Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Due Date</th>
                        <th>Present</th>
                        <th>Previous</th>
                        <th class="text-right">Kwh Used</th>
                        <th class="text-right">Power Bill</th>
                        <th class="text-right">2% VAT</th>
                        <th class="text-right">5% VAT</th>
                        <th class="text-right">Surcharges</th>
                        <th class="text-right">Total</th>
                    </thead>
                    <tbody>
                        @php
                            $powerBillTotal = 0;
                            $total = 0;
                            $has2Percent = false;
                            $has5Percent = false;
                            $surchargesTotal = 0;
                            $evat2Total = 0;
                            $evat5Total = 0;
                        @endphp
                        @foreach ($ledgers as $item)
                            <tr>
                                <td>{{ $item->AccountNumber }}</td>
                                <td>{{ $item->OldAccountNo }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->BillNumber }}</td>
                                <td>{{ $item->ConsumerType }}</td>
                                <td>{{ $item->ServiceDateFrom }}</td>
                                <td>{{ $item->ServiceDateTo }}</td>
                                <td>{{ $item->DueDate }}</td>
                                <td>{{ number_format($item->PresentKwh, 2) }}</td>
                                <td>{{ number_format($item->PreviousKwh, 2)  }}</td>
                                <td class="text-right">{{ number_format($item->KwhUsed, 2) }}</td>                                
                                <td class="text-right">{{ number_format((floatval($item->NetAmount) + floatval($item->Evat2Percent) + floatval($item->Evat5Percent)), 2) }}</td>
                                <td class="text-right">{{ $item->Evat2Percent != null ? number_format($item->Evat2Percent, 2) : 0 }}</td>
                                <td class="text-right">{{ $item->Evat5Percent != null ? number_format($item->Evat5Percent, 2) : 0 }}</td>
                                <td class="text-right">{{ number_format(Bills::assessDueBillAndGetSurcharge($item), 2) }}</td>
                                <td class="text-right">{{ number_format(floatval($item->NetAmount) + floatval(Bills::assessDueBillAndGetSurcharge($item)), 2) }}</td>
                            </tr>
                            @php
                                $powerBillTotal += (floatval($item->NetAmount) + floatval($item->Evat2Percent) + floatval($item->Evat5Percent));
                                $surchargesTotal += floatval(Bills::assessDueBillAndGetSurcharge($item));
                                $evat2Total += floatval($item->Evat2Percent);
                                $evat5Total += floatval($item->Evat5Percent);
                                $total += (floatval($item->NetAmount) + floatval(Bills::assessDueBillAndGetSurcharge($item)));

                                if ($item->Evat2Percent != null) {
                                    $has2Percent = true;
                                }

                                if ($item->Evat5Percent != null) {
                                    $has5Percent = true;
                                }
                            @endphp
                        @endforeach
                        {{-- TOTAL --}}
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right">{{ number_format($powerBillTotal, 2) }}</th>
                            <th class="text-right">{{ number_format($evat2Total, 2) }}</th>
                            <th class="text-right">{{ number_format($evat5Total, 2) }}</th>
                            <th class="text-right">{{ number_format($surchargesTotal, 2) }}</th>
                            <th class="text-right">{{ number_format($total, 2) }}</th>
                        </tr>
                    </tbody>
                </table>

                <div class="divider"></div>

                <div class="row">
                    {{-- EVAT CONFIG --}}
                    <div class="col-lg-8 col-md-7">
                        <address class="text-muted"><i>Settings</i></address>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-3">
                                            {{ Form::checkbox('Evat2Percent', 'Evat2Percent', $has2Percent ? true : false, ['class' => 'custom-checkbox', 'id' => 'Evat2Percent']) }}
                                        </div>
                                        <div class="col-lg-10 col-md-7">
                                            {!! Form::label('Evat2Percent', '2% VAT') !!}
                                        </div>     
                                    </div>                                   
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-3">
                                            {{ Form::checkbox('Evat5Percent', 'Evat5Percent', $has5Percent ? true : false, ['class' => 'custom-checkbox', 'id' => 'Evat5Percent']) }}
                                        </div>
                                        <div class="col-lg-10 col-md-7">
                                            {!! Form::label('Evat5Percent', '5% VAT') !!}
                                        </div>  
                                    </div>                                  
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- TOTAL --}}
                    <div class="col-lg-4 col-md-5">
                        <table class="table table-hover table-sm table-borderless">
                            <tr>
                                <th class="text-right">Amount Due:</th>
                                <th class="text-right">{{ number_format($powerBillTotal + $evat2Total + $evat5Total, 2) }}</th>
                            </tr>
                            <tr style="border-bottom: 1px solid #898989;">
                                <th class="text-right">(Add 5% Surcharges):</th>
                                <th class="text-right">{{ number_format($surchargesTotal, 2) }}</th>
                            </tr>
                            <tr>
                                <th class="text-right"><h4>Total Amount After Due:</h4></th>
                                <th class="text-right"><h4><strong>{{ number_format($total, 2) }}</strong></h4></th>
                            </tr>
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
        $(document).ready(function() {
            $('#Evat2Percent').change(function() {
                check2Percent()
            })

            $('#Evat5Percent').change(function() {
                check5Percent()
            })
        })

        function check2Percent() {
            if ($('#Evat2Percent').is(':checked')) {
                // ADD 2% DISCOUNT
                $.ajax({
                    url : '{{ route("bills.add-two-percent") }}',
                    type : 'GET',
                    data : {
                        MemberConsumerId : "{{ $memberConsumer->ConsumerId }}",
                        Period : "{{ $servicePeriod }}"
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while adding 2% discount')
                    }
                })
            } else {
                // REMOVE 2% DISCOUNT
                $.ajax({
                    url : '{{ route("bills.remove-two-percent") }}',
                    type : 'GET',
                    data : {
                        MemberConsumerId : "{{ $memberConsumer->ConsumerId }}",
                        Period : "{{ $servicePeriod }}"
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while adding 2% discount')
                    }
                })
            }
        }

        function check5Percent() {
            if ($('#Evat5Percent').is(':checked')) {
                // ADD 5% DISCOUNT
                $.ajax({
                    url : '{{ route("bills.add-five-percent") }}',
                    type : 'GET',
                    data : {
                        MemberConsumerId : "{{ $memberConsumer->ConsumerId }}",
                        Period : "{{ $servicePeriod }}"
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while adding 5% discount')
                    }
                })
            } else {
                // REMOVE 5% DISCOUNT
                $.ajax({
                    url : '{{ route("bills.remove-five-percent") }}',
                    type : 'GET',
                    data : {
                        MemberConsumerId : "{{ $memberConsumer->ConsumerId }}",
                        Period : "{{ $servicePeriod }}"
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while adding 5% discount')
                    }
                })
            }
        }
    </script>
@endpush