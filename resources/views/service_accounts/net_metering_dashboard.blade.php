@extends('layouts.app')

@section('content')
<div class="row">
    {{-- CONFIG --}}
    <div class="col-lg-5">
        <p style="padding-top: 8px;"><i class="fas fa-chart-line ico-tab"></i>Net Metering Dashboard and Monitoring</p>
    </div>
    <div class="col-lg-7" style="padding-top: 5px;"></div>

    {{-- EXPORTED --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 60vh">
            <div class="card-header">
                <span class="card-title">Exported</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr>
                            <td rowspan="2" style="width: 30px;">#</td>
                            <td colspan="3" class="text-center">Qualified End-User</td>
                            <td colspan="24" class="text-center">Monthly Monitoring (Past 12 months)</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="text-center">Consumer Name</td>
                            <td rowspan="2" class="text-center">Account Number</td>
                            <td rowspan="2" class="text-center">Address</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- IMPORTED --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 60vh">
            <div class="card-header">
                <span class="card-title">Imported</span>
            </div>
            <div class="card-body table-responsive p-0">

            </div>
        </div>
    </div>
</div>
@endsection