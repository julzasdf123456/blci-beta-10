@extends('layouts.app')

@section('content')
<div class="row">
    <div class='col-lg-12 col-md-12'>
        <br>
        <h4 class="text-center display-5">Search Tickets</h4>
        <br>
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-8 offset-md-2">
                <div class="input-group">
                    <input type="search" id='searchparam' class="form-control" placeholder="Type Name or Ticket Order Number">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default" id="searchBtn">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <div id="loader" class="spinner-border text-info gone" role="status" style="margin-left: 10px;">
                        <span class="sr-only">Loading...</span>
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
                $('#loader').removeClass('gone');
                $.ajax({
                    url : "{{ route('tickets.fetch-tickets') }}",
                    method : 'GET',
                    dataType : 'json',
                    data : { query : query },
                    success : function(data) {
                        $('#search-results').html(data.table_data);
                        $('#loader').addClass('gone');
                    },
                    error : function(error) {
                        console.log(error)
                        $('#loader').addClass('gone');
                    }
                });
            }

            $('#searchparam').on('keyup', function() {
                $('#loader').removeClass('gone');
                var aSearch = $.ajax({
                    url : "{{ route('tickets.fetch-tickets') }}",
                    method : 'GET',
                    dataType : 'json',
                    data : { query : this.value },
                    success : function(data) {
                        $('#search-results').html(data.table_data);
                        $('#loader').addClass('gone');
                    },
                    beforeSend : function() {
                        if (aSearch != null) {
                            $('#loader').removeClass('gone');
                            aSearch.abort();
                        }
                    },
                    error : function(error) {
                        console.log(error)
                        $('#loader').addClass('gone');
                    }
                });
            });

            $('#searchBtn').on('click', function() {
                fetchConsumers($('#searchparam').val());
            });            
        });
    </script>
@endpush

