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
            incrementCustomerDepositInterests()
        })
        
        // INVALIDATE LIFELINERS AND SENIOR CITIZENS
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

        // INCREMENT CUSTOMER DEPOSIT INTERESTS
        function incrementCustomerDepositInterests() {
            $.ajax({
                url : "{{ route('serviceAccounts.increment-customer-deposit-interests') }}",
                type : "GET",
                success : function(res) {
                    console.log('customer deposits incremented!')
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error incrementing customer deposit interests!'
                    })
                }
            })
        }
    </script>
@endpush
