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

@push('page_scripts')
    <script>
        $(document).ready(function() {
            invalidateLifelinersAndSeniorCitizens()
        })
        
        function invalidateLifelinersAndSeniorCitizens() {
            $.ajax({
                url : "{{ route('serviceAccounts.invalidate-lifeliners-and-scs') }}",
                type : "GET",
                data : {

                },
                success : function(res) {
                    console.log('Lifeliners and SCs invalidated!')
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error invalidating lifeliners and senior citizens!'
                    })
                }
            })
        }
    </script>
@endpush
