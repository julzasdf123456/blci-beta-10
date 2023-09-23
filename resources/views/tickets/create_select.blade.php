@extends('layouts.app')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <div class="card-title" style="width: 80%;">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <input class="form-control" id="old-account-no" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.5em; color: #b91400; font-weight: bold;" autofocus>
                    </div>
                    <div class="col-md-6">
                        <input autofocus type="text" class="form-control" placeholder="Search account name" id="params" value="{{ old('params') }}">
                    </div>
                    <div class="col-md-3">
                        <button id="searchBtn" class="btn btn-info"><i class="fas fa-search"></i></button>                   
                    </div>
                    <div id="loader" class="spinner-border text-info gone" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="card-tools">
                <a href="{{ route('tickets.create-new', [0]) }}" class="btn btn-tool text-danger"><i class="fas fa-forward"></i> Walk-in tickets</a>
            </div>
        </div>
        <div class="card-body">
            <table id="search-table" class="table table-hover">
                <thead>
                    <th>Account Number</th>
                    <th>Service Account Name</th>
                    <th>Address</th>
                    <th></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                callback.apply(context, args);
                }, ms || 0);
            };
        }

        $(document).ready(function() {
            $('#old-account-no').focus()

            $("#old-account-no").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });

            $("#old-account-no").off('keyup').on('keyup', function(event) {
                if (this.value.length == 12) {
                    var aSearch = $.ajax({
                        url : '{{ route("tickets.get-create-ajax") }}',
                        type : 'GET',
                        data : {
                            params : $('#params').val(),
                            oldacctno : this.value
                        },
                        success : function(response) {
                            $('#search-table tbody tr').remove();

                            $('#search-table tbody').append(response);

                            $('#loader').addClass('gone');
                        },
                        beforeSend : function() {
                            if (aSearch != null) {
                                aSearch.abort();
                                $('#loader').removeClass('gone');
                            }
                        },
                        error : function(error) {
                            alert(error)
                            $('#loader').addClass('gone');
                        }
                    });
                }
            })

            $('#params').keyup(delay(function(e) {
                $('#loader').removeClass('gone');
                var aSearch = $.ajax({
                    url : '{{ route("tickets.get-create-ajax") }}',
                    type : 'GET',
                    data : {
                        params : this.value,
                        oldacctno : $("#old-account-no").val()
                    },
                    success : function(response) {
                        $('#search-table tbody tr').remove();

                        $('#search-table tbody').append(response);

                        $('#loader').addClass('gone');
                    },
                    beforeSend : function() {
                        if (aSearch != null) {
                            aSearch.abort();
                            $('#loader').removeClass('gone');
                        }
                    },
                    error : function(error) {
                        alert(error)
                        $('#loader').addClass('gone');
                    }
                });
            }, 250));    

            $('#searchBtn').on('click',delay(function(e) {
                $('#loader').removeClass('gone');
                var aSearch = $.ajax({
                    url : '{{ route("tickets.get-create-ajax") }}',
                    type : 'GET',
                    data : {
                        params :$('#params').val(),
                    },
                    success : function(response) {
                        $('#search-table tbody tr').remove();

                        $('#search-table tbody').append(response);

                        $('#loader').addClass('gone');
                    },
                    beforeSend : function() {
                        if (aSearch != null) {
                            aSearch.abort();
                            $('#loader').removeClass('gone');
                        }
                    },
                    error : function(error) {
                        alert(error)
                        $('#loader').addClass('gone');
                    }
                });
            }, 250));  
        });
    </script>
@endpush