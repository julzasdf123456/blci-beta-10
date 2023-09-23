@extends('layouts.app')

@section('content')
    <div class="row">
        <div class='col-lg-12 col-md-12'>
            <br>
            <h4 class="display-5">
                    <lord-icon
                        src="https://cdn.lordicon.com/gsqxdxog.json"
                        trigger="loop"
                        delay="1500"
                        stroke="100"
                        colors="primary:#454545,secondary:#454545"
                        style="width:30px;height:30px;"
                        class="ico-tab-mini">
                    </lord-icon>
                Trash</h4>
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
                    url : "{{ route('serviceConnections.fetch-service-connection-trash') }}",
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