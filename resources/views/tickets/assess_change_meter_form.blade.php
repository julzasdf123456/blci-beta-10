@php
    use App\Models\Tickets;
    use App\Models\Users;
@endphp

@extends('layouts.app')

@section('content') 
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Assess Change Meter - {{ $ticket->ConsumerName }}</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        <div class="col-lg-6">
            {{-- Complaint --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Complaint</span>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Ticket ID</td>
                            <th>{{ $ticket->id }}</th>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <th>{{ Tickets::getAddress($ticket) }}</th>
                        </tr>
                        <tr>
                            <td>Reason</td>
                            <th>{{ $ticket->Reason }}</th>
                        </tr>
                        <tr>
                            <td>Date of Complaint</td>
                            <th>{{ date('F d, Y,  h:i A', strtotime($ticket->created_at)) }}</th>
                        </tr>
                        <tr>
                            <td>Current Meter Brand</td>
                            <th>{{ $ticket->CurrentMeterBrand }}</th>
                        </tr>
                        <tr>
                            <td>Current Meter No</td>
                            <th>{{ $ticket->CurrentMeterNo }}</th>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    
                    {!! Form::open(['route' => 'tickets.update-change-meter-assessment']) !!}
                        <div class="row">
                            <div class="form-group col-lg-9 col-md-8">
                                <label for="Crew">Assign Crew</label>
                                {!! Form::select('CrewAssigned', $crew, null, ['class' => 'form-control',]) !!}
                            </div>

                            <div class="form-group col-lg-3 col-md-4">
                                <label for="Forward">Proceed</label>
                                {!! Form::submit('Forward to Crew', ['class' => 'btn btn-primary', 'id' => 'Forward']) !!}
                            </div>
                            
                            <div class="form-group col-lg-12">
                                <input type="hidden" name="id" value="{{ $ticket->id }}"/>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            {{-- Latest Reading/Billing --}}
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title">Latest Reading</span>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table">
                        <thead>
                            <th>Billing Month</th>
                            <th>Kwh Used</th>
                            <th>Meter Reader</th>
                        </thead>
                        <tbody>
                            @if ($latestReading != null)
                                @foreach ($latestReading as $item)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td>{{ number_format($item->KwhUsed, 2) }}</td>
                                        <td>
                                            {{ Users::find($item->MeterReader) != null ? Users::find($item->MeterReader)->name : 'n/a' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection