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
                <div class="col-sm-12">
                    <h4>Detailed Adjustment Report</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            {{-- HEADER --}}
            <div class="card shadow-none">
                <div class="card-body ">
                    {!! Form::open(['route' => 'bills.adjustment-reports-with-gl', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Area</label>
                            <select id="Area" name="Area" class="form-control form-control-sm">
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Area']) && $_GET['Area']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ isset($_GET['ServicePeriod']) && $months[$i]==$_GET['ServicePeriod'] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            {{-- DATA DISPLAY --}}
            <div class="card shadow-none" style="height: 70vh;">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <th>Account No</th>
                            <th>Account Name</th>
                            <th>Address</th>
                            <th>Adjusted Kwh</th>
                            <th>Adjusted Amount</th>
                        </thead>   
                        <tbody>
                        </tbody> 
                    </table>               
                </div>
            </div>
        </div>  
    </div>
@endsection
