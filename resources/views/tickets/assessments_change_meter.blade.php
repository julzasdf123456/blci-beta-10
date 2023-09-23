@php
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Change Meter Assessments</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                @include('flash::message')

                <div class="clearfix"></div>

                <p>Change Meter Requests Pending for Assessment</p>
                <table class="table table-hover">
                    <thead>
                        <th>Ticket No</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Reason</th>
                        <th>Contact Number</th>
                        <th>Meter No</th>
                        <th>Remarks</th>
                        <th width="40px"></th>
                    </thead>
                    <tbody>
                        @if ($tickets != null)
                            @foreach ($tickets as $item)
                                <tr>
                                    <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                    <td>{{ $item->ConsumerName }}</td>
                                    <td>{{ Tickets::getAddress($item) }}</td>
                                    <td>{{ $item->Reason }}</td>
                                    <td>{{ $item->ContactNumber }}</td>
                                    <td>{{ $item->CurrentMeterNo }}</td>
                                    <td>{{ $item->Notes }}</td>
                                    <td>
                                        <a href="{{ route('tickets.assess-change-meter-form', [$item->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-forward"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection