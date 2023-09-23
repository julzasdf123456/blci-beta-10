@php
    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Print BAPA Reading List - Search BAPA</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3 offset-lg-3">
                <input type="text" id="search-field" placeholder="Search BAPA Name" class="form-control" autofocus>
            </div>
            <div class="col-lg-2">
                <select name="" id="towns" class="form-control">
                    <option value="All">All</option>
                    @foreach ($towns as $item)
                        <option value="{{ $item->id }}">{{ $item->Town }}</option>
                    @endforeach
                </select>
            </div>
            <div class="class-col-lg-2">
                <button class="btn btn-primary" id="search-btn"><i class="fas fa-search ico-tab"></i>Search</button>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-10 offset-md-1">
                <br>

                <table class="table table-hover table-sm" id="res-table">
                    <thead>
                        <th>BAPA Name</th>
                        <th>Print</th>
                        <th>Town/Area/District</th>
                        <th>Number of Accounts</th>
                        <th style="max-width: 30%;">Routes in This BAPA</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-select-period" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>Select Billing Month To Print</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ServicePeriodPrint">Billing Month</label>
                    <select name="ServicePeriodPrint" id="ServicePeriodPrint" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="print-reading-list"><i class="fas fa-print ico-tab-mini"></i>Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script>
    var bapa = ""
    $(document).ready(function() {
        $('#search-field').on("keyup", function(e) {
            e.stopPropagation()
            e.preventDefault()
            var len = this.value.length

            if (len > 3) {
                searchBapa(this.value, $('#towns').val())
            }
        })

        $('#towns').on('change', function() {
            searchBapa($('#search-field').val(), this.value)
        })

        $('#search-btn').on('click', function() {
            searchBapa($('#search-field').val(), $('#towns').val())
        })

        $('#print-reading-list').on('click', function() {
            window.location.href = "{{ url('/readings/print-bapa-reading-list-to-paper') }}" + "/" + bapa + "/" + $('#ServicePeriodPrint').val()
        })
    })

    function selectPeriod(bapaName) {
        bapa = bapaName

        $('#modal-select-period').modal('show')
    }

    function searchBapa(param, town) {
        $('#res-table tbody tr').remove()
        $.ajax({
            url : "{{ route('readings.search-print-bapa-reading-list') }}",
            type : 'GET',
            data : {
                BAPA : param,
                Town : town,
            },
            success : function(res) {
                $('#res-table tbody').append(res)
            },
            error : function(err) {
                alert('An error occurred during the search')
            }
        })
    }
</script>
@endpush
