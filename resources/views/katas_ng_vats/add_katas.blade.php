@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Katas Ng VAT - Add Accounts</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-5 col-md-6">
            <div class="card shadow-none" style="height: 80vh;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Katas Ng VAT Amount</label>
                                <input type="number" step="any" class="form-control" id="totalKatas" placeholder="Input Katas Ng VAT Amount" value="{{ $katas->Balance }}" readonly>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Katas Deductible</label>
                                <input type="number" step="any" class="form-control" id="amount" placeholder="Input Katas Deductible" value="500">
                            </div>                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Search Account No.</label>
                                <input class="form-control" id="old-account-no" name="oldaccount" placeholder="Input Account Number" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12">
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover" id="account-results">
                        <thead>
                            <th>Account No</th>
                            <th>Consumer Name</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ALL KATAS --}}
        <div class="col-lg-7 col-md-6">
            <div class="card shadow-none" style="height: 80vh;">
                <div class="card-header">
                    <span class="card-title">Accounts with Katas ng VAT (<i>Press <strong>F3</strong> to search</i>)</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered" id="all-katas">
                        <thead>
                            <th>Account No</th>
                            <th>Consumer Name</th>
                            <th>Account Status</th>
                            <th class="text-right">Katas Balance</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

            fetchKatas()

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

            $('#old-account-no').keyup(function() {
                if (this.value.length == 12) {
                    searchAccount(this.value)
                }   
            })
        })

        function searchAccount(acctNo) {            
            $.ajax({
                url : "{{ route('katasNgVats.search-account') }}",
                type : 'GET',
                data : {
                    AccountNumber : acctNo,
                },
                success : function(res) {
                    $('#account-results tbody tr').remove()
                    $('#account-results tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error fetching accounts',
                        icon : 'error'
                    })
                }
            })
        }

        function fetchKatas() {
            $.ajax({
                url : "{{ route('katasNgVats.fetch-katas') }}",
                type : 'GET',
                data : {
                    SeriesNo : '{{ $seriesNo }}'
                },
                success : function(res) {
                    $('#all-katas tbody tr').remove()
                    $('#all-katas tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting Katas ng VAT data',
                        icon : 'error'
                    })
                }
            })
        }

        function addToKatas(acctId) {
            var amt = $('#amount').val()
            if (jQuery.isEmptyObject(amt)) {
                Swal.fire({
                    text : 'Pleas input Amount to add to Katas ng VAT',
                    icon : 'error'
                })
            } else {
                $.ajax({
                    url : "{{ route('katasNgVats.add-account-to-katas') }}",
                    type : 'GET',
                    data : {
                        AccountNumber : acctId,
                        Amount : amt,
                        SeriesNo : '{{ $seriesNo }}'
                    },
                    success : function(res) {
                        if (res == 'exists') {
                            Swal.fire({
                                text : 'This account already has a Katas Ng VAT Deduction. Delete first the data and try adding again.',
                                icon : 'warning'
                            })
                        } else {
                            fetchKatas()
                            $('#totalKatas').val(res)
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Katas ng VAT Added',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    },
                    error : function(err) {
                        Swal.fire({
                            title : 'Error adding Katas ng VAT',
                            icon : 'error'
                        })
                    }
                })
            }
        }

        function deleteKatas(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('katasNgVats.delete-katas') }}",
                        type  : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            $('#totalKatas').val(res)
                            fetchKatas()
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Katas ng VAT Deleted',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        },
                        error : function(err) {
                            Swal.fire({
                                    title : 'Error deleted Katas ng VAT',
                                    icon : 'error'
                                })
                        }
                    })
                }
            })
            
        }
    </script>
@endpush