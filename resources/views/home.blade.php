@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        @include('service_connections.dashboard_service_connection_summary')
        
        @include('tickets.dashboard_ticket_summary')

        @include('tickets.ticket_crew_monitor')
    </div>
</div>
@endsection
