@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Account Excemptions</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-5">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-header">
                <div class="row">                    
                    <div class="form-group col-6">
                        <label for="old-account-no">Search Account No.</label>
                        <input class="form-control" id="old-account-no" name="oldaccount" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.1em; font-weight: bold;">
                    </div>
                    <div class="form-group col-6">
                        <label for="reason">Reason for Excemption</label>
                        <input class="form-control" id="reason" name="reason" maxlength="50">
                    </div>
                </div> 
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover" id="results-table">
                    <thead>
                        <th>Acct. No.</th>
                        <th>Consumer Name</th>
                        <th>Bill Amount</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-header">
                <span class="card-title">Excempted Accounts</span>

                <div class="card-tools row">
                    
                    <div class="form-group col-12">
                        <select name="Town" id="Town" class="form-control">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}">{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm" id="excempted-table">
                    <thead>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Reason</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="card-footer">
                <button id="print-excemptions" class="btn btn-link text-primary"><i class="fas fa-print"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('page_scripts')
    <script>
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

            $("#old-account-no").keyup(function(e) {
                searchAccount(this.value)
            })

            getExcemptions($('#Town').val())

            $('#Town').on('change', function() {
                getExcemptions(this.value)
                searchAccount($("#old-account-no").val())
            })

            $('#print-excemptions').on('click', function() {
                window.location.href = "{{ url('/excemptions/print-excemptions') }}" + "/" + $('#Town').val()
            })
        })

        function searchAccount(accountNo) {
            if (accountNo.length > 6) {
                $('#results-table tbody tr').remove()
                $.ajax({
                    url : "{{ route('excemptions.search-account-excemption') }}",
                    type : 'GET',
                    data : {
                        AccountNumber : accountNo,
                    },
                    success : function(res) {
                        $('#results-table tbody').append(res)
                    },
                    error : function(err) {
                        Swal.fire({
                            title : 'Error getting data',
                            icon : 'error'
                        })
                    }
                })
            }            
        }

        function getExcemptions(town) {
            $.ajax({
                url : "{{ route('excemptions.get-excemptions-ajax') }}",
                type : 'GET',
                data : {
                    Town : town,
                },
                success : function(res) {
                    $('#excempted-table tbody tr').remove()
                    $('#excempted-table tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting excempted accounts',
                        icon : 'error'
                    })
                }
            })
        }

        function addExcemption(accountNo) {
            $.ajax({
                url : "{{ route('excemptions.add-excemption') }}",
                type : 'GET',
                data : {
                    AccountNumber : accountNo,
                    ServicePeriod : $('#ServicePeriod').val(),
                    Reason : $('#reason').val()
                },
                success : function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Account Added to Excemptions'
                    })
                    getExcemptions($('#Town').val())
                    $('#' + accountNo).remove()
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error adding account to excemptions',
                        icon : 'error'
                    })
                }
            })
        }

        function removeExcemption(id) {
            $.ajax({
                url : "{{ route('excemptions.remove-excemption') }}",
                type : 'GET',
                data : {
                    id : id
                },
                success : function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Account Removed from Excemptions'
                    })
                    getExcemptions($('#Town').val())
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error removing account to excemptions',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush
