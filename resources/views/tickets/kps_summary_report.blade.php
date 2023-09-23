@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Ticket Summary Report - NEA KPS</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            {!! Form::open(['route' => 'tickets.kps-summary-report', 'method' => 'GET']) !!}
            <div class="row">
                {{-- FROM --}}
                <div class="form-group col-lg-2">
                    <label for="From">From</label>
                    {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'From', 'required' => true]) !!}
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
                    {!! Form::text('To',  isset($_GET['To']) ? $_GET['To'] : '', ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Date', 'id' => 'To', 'required' => true]) !!}
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
                    <select id="Town" name="Town" class="form-control form-control-sm">
                        <option value="All">All</option>
                        @foreach ($towns as $item)
                            <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- BUTTONS --}}
                <div class="form-group col-lg-2">
                    <label style="opacity: 0; width: 100%;">Action</label>
                    <button type="submit" class="btn btn-primary btn-sm" id="filterBtn" title="Filter"><i class="fas fa-check"></i></button>
                    <button class="btn btn-success btn-sm" id="downloadBtn" title="Download Excel"><i class="fas fa-download"></i></button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        {{-- TABLE --}}
        <div class="col-lg-12">
            <table class="table table-hover table-resposive table-sm table-bordered" id="results-table">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2" style="width: 30px;">#</th>
                        <th class="text-center" rowspan="2">Nature of Complaints</th>
                        <th class="text-center" colspan="2">No. of Complaints</th>
                    </tr>
                    <tr>
                        <th class="text-center">Received *</th>
                        <th class="text-center">Acted Upon *</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1</strong></td>
                        <td><strong>No Light/Power</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1.a</td>
                        <td>Primary Line</td>
                        <td>{{ $data->Received1a }}</td>
                        <td>{{ $data->Acted1a }}</td>
                    </tr>
                    <tr>
                        <td>1.b</td>
                        <td>Distribution Transformer/ Secondary Line</td>
                        <td>{{ $data->Received1b }}</td>
                        <td>{{ $data->Acted1b }}</td>
                    </tr>
                    <tr>
                        <td>1.c</td>
                        <td>Residence No Power</td>
                        <td>{{ $data->Received1c }}</td>
                        <td>{{ $data->Acted1c }}</td>
                    </tr>
                    <tr>
                        <td><strong>2</strong></td>
                        <td><strong>Power Quality Complaint</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2.a</td>
                        <td>Low Voltage</td>
                        <td>{{ $data->Received2a }}</td>
                        <td>{{ $data->Acted2a }}</td>
                    </tr>
                    <tr>
                        <td>2.b</td>
                        <td>Fluctuating Voltage</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2.c</td>
                        <td>Loose Connection</td>
                        <td>{{ $data->Received2c }}</td>
                        <td>{{ $data->Acted2c }}</td>
                    </tr>
                    <tr>
                        <td><strong>3</strong></td>
                        <td><strong>Complaints/ Services on Service Drop</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3.a</td>
                        <td>Reroute Service Drop</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3.b</td>
                        <td>Change/ Upgrade Service Drop</td>
                        <td>{{ $data->Received3b }}</td>
                        <td>{{ $data->Acted3b }}</td>
                    </tr>
                    <tr>
                        <td>3.c</td>
                        <td>Others (e.g. Broken, Sagging, Sparking, etc.)</td>
                        <td>{{ $data->Received3c }}</td>
                        <td>{{ $data->Acted3c }}</td>
                    </tr>
                    <tr>
                        <td><strong>4</strong></td>
                        <td><strong>Distribution Pole Complaint and Others</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4.a</td>
                        <td>Rotten Pole</td>
                        <td>{{ $data->Received4a }}</td>
                        <td>{{ $data->Acted4a }}</td>
                    </tr>
                    <tr>
                        <td>4.b</td>
                        <td>Leaning Pole</td>
                        <td>{{ $data->Received4b }}</td>
                        <td>{{ $data->Acted4b }}</td>
                    </tr>
                    <tr>
                        <td>4.c</td>
                        <td>Relocation of Pole</td>
                        <td>{{ $data->Received4c }}</td>
                        <td>{{ $data->Acted4c }}</td>
                    </tr>
                    <tr>
                        <td>4.d</td>
                        <td>Distribution Transformer Replacement (e.g. Busted Transformer)</td>
                        <td>{{ $data->Received4d }}</td>
                        <td>{{ $data->Acted4d }}</td>
                    </tr>
                    <tr>
                        <td><strong>5</strong></td>
                        <td><strong>Complaints on kWh Meter</strong></td>
                        <td>{{ $data->Received5 }}</td>
                        <td>{{ $data->Acted5 }}</td>
                    </tr>
                    <tr>
                        <td><strong>6</strong></td>
                        <td><strong>Others (Board, Management, Employees, etc.) </strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><strong>7</strong></td>
                        <td><strong>Other Verified Complaints</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7.a</td>
                        <td>Endorsed by Department of Energy (DoE)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7.b</td>
                        <td>Endorsed by Presidential Action Center (PAC)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7.c</td>
                        <td>Endorsed by Civil Service Commission (CSC)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7.d</td>
                        <td>National Electrification Administration (NEA) Referral</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#downloadBtn').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/tickets/download-kps-summary-report') }}" + "/" + $('#Town').val() + "/" + $('#From').val() + "/" + $('#To').val() 
            })
        })    
    </script>    
@endpush