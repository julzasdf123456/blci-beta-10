@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Ticket Tally Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="row">

            {{-- FROM --}}
            <div class="form-group col-lg-2">
                <label for="From">From</label>
                {!! Form::text('From', null, ['class' => 'form-control', 'placeholder' => 'Select Date', 'id' => 'From']) !!}
            </div>
            @push('page_scripts')
                <script type="text/javascript">
                    $('#From').datetimepicker({
                        format: 'YYYY-MM-DD',
                        useCurrent: true,
                        sideBySide: true
                    })
                </script>
            @endpush

            {{-- TO --}}
            <div class="form-group col-lg-2">
                <label for="To">To</label>
                {!! Form::text('To', null, ['class' => 'form-control', 'placeholder' => 'Select Date', 'id' => 'To']) !!}
            </div>
            @push('page_scripts')
                <script type="text/javascript">
                    $('#To').datetimepicker({
                        format: 'YYYY-MM-DD',
                        useCurrent: true,
                        sideBySide: true
                    })
                </script>
            @endpush

            {{-- AREA --}}
            <div class="form-group col-lg-2">
                <label for="Town">Area</label>
                <select id="Town" class="form-control">
                    <option value="All">All</option>
                    @foreach ($towns as $item)
                        <option value="{{ $item->id }}">{{ $item->Town }}</option>
                    @endforeach
                </select>
            </div>

            {{-- BUTTONS --}}
            <div class="form-group col-lg-3">
                <label style="opacity: 0; width: 100%;">Action</label>
                <button class="btn btn-primary" id="filterBtn" title="Filter"><i class="fas fa-check"></i></button>
                <button class="btn btn-success" id="download" title="Download"><i class="fas fa-download"></i></button>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="col-lg-6">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-header">
                <span class="card-title">Requests</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed text-nowrap table-sm table-bordered" id="results-table-request">
                    <thead> 
                        <th class="text-center">Ticket</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Attended<br>Executed</th>
                        <th class="text-center">Unattended<br>Unexecuted</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>        
    </div>

     {{-- TABLE COMPLAINS --}}
     <div class="col-lg-6">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-header">
                <span class="card-title">Complaints</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-head-fixed text-nowrap table-sm table-bordered" id="results-table-complains">
                    <thead>
                        <th class="text-center">Ticket</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Attended<br>Executed</th>
                        <th class="text-center">Unattended<br>Unexecuted</th>
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
            $('#filterBtn').on('click', function() {
                $('#results-table-request tbody tr').remove()
                getTally('Request', 'results-table-request')

                $('#results-table-complains tbody tr').remove()
                getTally('Complain', 'results-table-complains')
            })

            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/tickets/download-ticket-tally') }}" + "/" + $('#Town').val() + "/" + $('#From').val() + "/" + $('#To').val()
            })
        })

        function getTally(type, table) {
            $.ajax({
                url : "{{ route('tickets.get-ticket-tally') }}",
                type : 'GET',
                data : {
                    Type : type,
                    From : $('#From').val(),
                    To : $('#To').val(),
                    Town : $('#Town').val()
                },
                success : function(res) {
                    $.each(res, function(index, element) {
                        $('#' + table + ' tbody').append(addRow(res[index]['id'], res[index]['Name'], res[index]['ReceivedTotal'], res[index]['ExecutedTotal'], res[index]['NotExecutedTotal']))
                    })
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Error fetching tickets',
                        icon : 'error'
                    })
                }
            })
        }

        function addRow(id, name, total, executed, unexecuted) {
            if (total == 'Parent') {
                return "<tr>" +
                            "<th colspan='4'>" + name +  "</th>" + 
                        "</tr>"
            } else {
                if (name == 'Total') {
                    return "<tr>" +
                            "<th>" + name +  "</th>" + 
                            "<th class='text-info text-right'>" + (total=='0' ? '' : total) +  "</th>" + 
                            "<th class='text-success text-right'>" + (executed=='0' ? '' : executed) +  "</th>" + 
                            "<th class='text-danger text-right'>" + (unexecuted=='0' ? '' : unexecuted) +  "</th>" + 
                        "</tr>"
                } else {
                    return "<tr>" +
                        "<td style='padding-left: 50px;'>" + name +  "</td>" + 
                        "<th class='text-info text-right'>" + (total=='0' ? '' : total) +  "</th>" + 
                        "<th class='text-success text-right'>" + (executed=='0' ? '' : executed) +  "</th>" + 
                        "<th class='text-danger text-right'>" + (unexecuted=='0' ? '' : unexecuted) +  "</th>" + 
                    "</tr>"
                }                
            }
            
        }
    </script>
@endpush