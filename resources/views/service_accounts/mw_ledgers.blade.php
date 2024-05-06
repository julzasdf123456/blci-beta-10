@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 36; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Upload Customer Ledger CSV/Excel File</h4>
                    <p class="text-muted"><i><strong>NOTE: </strong></i>For admin, make sure the memory_limit and max_execution_time are set to it's highest possible values to avoid clogging the server as these Excel files are huge.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-md-3 col-md-8 offset-md-2">

            @include('flash::message')

            @include('adminlte-templates::common.errors')

            <div class="clearfix"></div>

            <div class="card">
                <form method="POST" enctype="multipart/form-data" action="{{ route('serviceAccounts.validate-mw-ledgers') }}" >
                <div class="card-header">
                    <span class="card-title">Upload .txt File</span>
                </div>
                <div class="card-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        <label for="ServicePeriod">Select Billing Month</label>
                        <br>
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control" required>
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                        <span class="text-danger">{{ $errors->first('ServicePeriod') }}</span>
                    </div>

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        {!! Form::label('file', 'Select Customer Ledger CSV/Excel File (.csv, .xlsx):') !!}
                        <br>
                        <input type="file" name="file" placeholder="Choose File" id="file" accept=".csv,.xls,.xlsx" required>
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
