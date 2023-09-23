@extends('layouts.app')

@section('content')
    <div class="row">
        <div class='col-lg-12 col-md-12'>
            <br>
            <h4 class="text-center display-5">Select Member Consumer/Account Holder</h4>
            <br>
            <div class="row">
                <!-- SEARCH BAR -->
                <div class="col-md-8 offset-md-2">
                    <div class="input-group">
                        <input type="search" id='searchparam' class="form-control" placeholder="Type Name or Member Consumer ID">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default" id="searchBtn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <!-- SEARCH RESULTS -->
            <table class="table table-hover" id="res-table">
                <thead>
                    <th>Consumer ID</th>
                    <th>Consumer Name</th>
                    <th>Consumer Address</th>
                    <th></th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        
    </div>
@endsection


@push('page_scripts')
    <script>
        $(document).ready(function() {

            fetchConsumers('');

            function fetchConsumers(query = '') {
                $('#res-table tbody tr').remove()
                $.ajax({
                    url : "{{ route('bills.fetch-member-consumers') }}",
                    method : 'GET',
                    data : { 
                        query : query 
                    },
                    success : function(data) {
                        $('#res-table tbody').append(data)
                    }
                });
            }

            $('#searchparam').on('keyup', function() {
                fetchConsumers(this.value);
            });

            $('#searchBtn').on('click', function() {
                fetchConsumers($('#searchparam').val());
            });            
        });
    </script>
@endpush