@extends('layouts.app')

@section('content')
<p style="padding-top: 8px;"><i class="fas fa-chart-line ico-tab"></i>Collection Dashboard</p>
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-coins ico-tab"></i>Collection Summary Per Area <i class="text-muted" id="collection-per-area-subtitle">(This Month)</i></span>

                <div class="card-tools">
                    <div class="row">
                        <div class="col">
                            <input type="text" id="From" class="form-control form-control-sm" placeholder="From">
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#From').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>
                        <div class="col">
                            <input type="text" id="To" class="form-control form-control-sm" placeholder="To">
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#To').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>
                        <div class="col">
                            <button id="filter-collection-per-day" class="btn btn-sm btn-warning"><i class="fas fa-filter"></i>Filter</button>    
                        </div>                        
                    </div>                    
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered table-sm" id="collection-per-area">
                    <thead>
                        <th>Area</th>
                        <th class="text-right">kWh Sales</th>
                        <th class="text-right">Total<br>Consumers</th>
                        <th class="text-right">Total<br>Surcharges</th>
                        <th class="text-right">Total 2%</th>
                        <th class="text-right">Total 5%</th>
                        <th class="text-right">Total OCL</th>
                        <th class="text-right">Total<br>Deductions</th>
                        <th class="text-right">Power Bills<br>Total</th>
                        <th class="text-right">Miscellaneous<br>Payments</th>
                        <th class="text-right">Overall<br>Total</th>
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
        $(document).ready(function() {
            fetchCollectionPerArea(null, null)

            $('#filter-collection-per-day').on('click', function() {
                fetchCollectionPerArea($('#From').val(), $('#To').val())
            })
        })

        function fetchCollectionPerArea(from, to) {
            $('#collection-per-area tbody tr').remove()
            $.ajax({
                url : "{{ route('dCRSummaryTransactions.get-collection-per-area') }}",
                type : 'GET',
                data : {
                    From : from,
                    To : to,
                },
                success : function(res) {
                    $('#collection-per-area tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error fetching collection per area',
                        icon : error
                    })
                }
            })
        }
    </script>
@endpush