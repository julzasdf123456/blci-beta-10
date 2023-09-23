@php    
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Sales and DCR Monitoring</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">

    @include('flash::message')

    <div class="clearfix"></div>

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-3">
            <div class="card">
                {!! Form::open(['route' => 'dCRSummaryTransactions.sales-dcr-monitor', 'method' => 'GET']) !!}
                <div class="card-body">
                    <!-- From Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('From', 'From:') !!}
                        {!! Form::text('From', $from, ['class' => 'form-control','id'=>'From']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#From').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <!-- To Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('To', 'To:') !!}
                        {!! Form::text('To', $to, ['class' => 'form-control','id'=>'To']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#To').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <!-- Tellers Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('Teller', 'Teller:') !!}
                        <select name="Teller" class="form-control">
                            <option value="All">All</option>
                            @foreach ($tellers as $item)
                                <option value="{{ $item->id }}" {{ $tellerSelect == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Office Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('Office', 'Office:') !!}
                        <select name="Office" class="form-control">
                            <option value="All">All</option>
                            @foreach ($offices as $item)
                                <option value="{{ $item->Office }}" {{ $officeSelect == $item->Office ? 'selected' : '' }}>{{ $item->Office }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    {!! Form::submit('Go', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header border-0">
                    
                </div>
                <div class="card-body table-responsive">
                    {{-- DCR --}}
                    <p><strong>Daily Collection from {{ date('F d, Y', strtotime($from)) }} - {{ date('F d, Y', strtotime($to)) }}</strong></p>
                    <table class="table table-hover table-sm table-borderless">
                        <thead>
                            <th>GL Code</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                        </thead>
                        <tbody>
                            @php
                                $totalDcr = 0;
                            @endphp
                            @foreach ($collection as $item)
                                @if (floatval($item->Amount) == 0)
                                    
                                @else
                                    <tr>
                                        <td>{{ $item->GLCode }}</td>
                                        <td>{{ $item->Description }}</td>
                                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                    </tr>
                                    
                                    @php
                                        $totalDcr = $totalDcr + floatval($item->Amount);
                                    @endphp
                                @endif
                                
                            @endforeach
                            <tr>
                                <th>Total</th>
                                <th></th>
                                <th class="text-right">{{ number_format($totalDcr, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="divider"></div>

                    {{-- SALES --}}
                    <p><strong>Sales from {{ date('F d, Y', strtotime($from)) }} - {{ date('F d, Y', strtotime($to)) }}</strong></p>
                    <table class="table table-hover table-sm table-borderless">
                        <thead>
                            <th>GL Code</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                        </thead>
                        <tbody>
                            @php
                                $totalSales = 0;
                            @endphp
                            @foreach ($sales as $item)
                                @if (floatval($item->Amount) == 0)
                                    
                                @else
                                    <tr>
                                        <td>{{ $item->GLCode }}</td>
                                        <td>{{ $item->Description }}</td>
                                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                    </tr>
                                    @php
                                        $totalSales = $totalSales + floatval($item->Amount);
                                    @endphp
                                @endif                                
                            @endforeach
                            <tr>
                                <th>Total</th>
                                <th></th>
                                <th class="text-right">{{ number_format($totalSales, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection