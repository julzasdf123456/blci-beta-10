@extends('layouts.app')

@section('content')
    <div class="row">
        <div class='col-lg-12 col-md-12'>
            <br>
            <h4 class="text-center display-5">Search Service Connections</h4>
            <br>
            <div class="row">
                <!-- SEARCH BAR -->
                <div class="col-md-8 offset-md-2">
                    <div class="input-group">
                        <input type="search" id='searchparam' class="form-control" placeholder="Type Name or Service Connection ID">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default" id="searchBtn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEARCH RESULTS -->
            <div id="search-results" style="margin-top: 15px;">
                                  
            </div>
        </div>        
    </div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

            fetchConsumers('');

            function fetchConsumers(query = '') {
                $.ajax({
                    url : "{{ route('serviceConnections.fetch-service-connections') }}",
                    method : 'GET',
                    dataType : 'json',
                    data : { query : query },
                    success : function(data) {
                        $('#search-results').html(data.table_data);
                        // console.log(query);
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