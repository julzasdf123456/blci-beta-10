@php
    use Illuminate\Support\Facades\Auth; 
    use App\Models\IDGenerator;
    use App\Models\ORAssigning;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Bills Payment OR Cancellation Console</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- SEARCH --}}
        <div class="col-lg-5 col-md-6">
            <div class="card" style="height: 70vh;">
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="search-field" placeholder="Search OR Number or Consumer" autofocus>
                        </div>
                        <div class="col-lg-4">
                            <div class="card-tools">
                                <button class="btn btn-primary" id="search-btn">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm" id="result-table">
                        <thead>
                            <th>OR Number</th>
                            <th>Account No</th>
                            <th>Consumer Name</th>
                            <th>OR Date</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <div class="col-lg-7 col-md-6">
            <div class="card" style="height: 70vh;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-8">
                            <input type="text" class="form-control" placeholder="Why do you wanna cancel this OR? Provide remarks here." id="notes">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button class="btn btn-danger" id="cancel-or-btn">Cancel OR</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover" id="bills-table">
                        <thead>
                            <th>Bill No.</th>
                            <th>Account Name</th>
                            <th>Billing Month</th>
                            <th>OR Number</th>
                            <th>OR Date</th>
                            <th>Amount Due</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var orNumberActive = ""

        $(document).ready(function() {
            $('#search-field').bind("keyup", function(event) {  
                event.preventDefault()
                var letterCount = this.value.length;

                if (letterCount > 4) {
                    performSearchOR(this.value)
                }   
                
                return false;
            })

            $('#search-btn').on('click', function() {
                performSearchOR($('#search-field').val())
            })

            $('#cancel-or-btn').on('click', function() {
                if (jQuery.isEmptyObject(orNumberActive)) {
                    alert('Select payment first!')
                } else {
                    if (confirm('Are you sure you want to cancel this OR?')) {
                        $.ajax({
                            url : '{{ route("paidBills.request-cancel-or") }}',
                            type : 'GET',
                            data : {
                                orNo : orNumberActive,
                                Notes : $('#notes').val(),
                            },
                            success : function(res) {
                                window.location.reload()
                            },
                            error : function(err) {
                                alert('An error occurred while cancelling the OR. \n' . err)
                            }
                        })
                    }
                }
            })
        })

        function performSearchOR(value) {
            $('#result-table tbody tr').remove()
            $.ajax({
                url : '{{ route("paidBills.search-or") }}',
                type : 'GET',
                data : {
                    query : value
                },
                success : function(res) {
                    $('#result-table tbody').append(res)
                },
                error : function(err) {
                    $('#result-table tbody tr').remove()
                    alert('An error occurred during the search')
                }
            })
        }

        function fetchDetails(orNo) {
            $('#bills-table tbody tr').remove()
            orNumberActive = orNo
            $.ajax({
                url : '{{ route("paidBills.fetch-or-details") }}',
                type : 'GET',
                data : {
                    orNo : orNo,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $('#bills-table tbody').append(res)
                    }
                },
                error : function(err) {
                    alert('An error occurred while fetching the data')
                }
            })
        }
    </script>
@endpush