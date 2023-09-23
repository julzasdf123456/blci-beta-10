@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account Migration Wizzard</h4>
                    <i class="text-muted">Step 2. Import meter details and assess computation</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-3 col-md-4">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Consumer Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-borderless table-sm">
                    <tr>
                        <th><i class="fas fa-user-circle ico-tab"></i>{{ $serviceAccount->ServiceAccountName }}</th>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-marker-alt ico-tab"></i>{{ ServiceConnections::getAddress($serviceAccount) }}</td>
                    </tr>
                    <tr>
                        <td title="Account Number Format: New Account Number (Old/Legacy Account Number)"><i class="fas fa-user-alt ico-tab"></i>{{ $serviceAccount->id }} ({{ $serviceAccount->OldAccountNo }})</td>
                    </tr>
                    <tr>
                        <td title="Area Code"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->AreaCode }}</td>
                    </tr>
                    <tr>
                        <td title="Sequence Number"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->SequenceCode }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><strong>Step 2. </strong>Meter Information</span>
            </div>
            {!! Form::open(['route' => 'billingMeters.store']) !!}
            <div class="card-body">
                {{-- HIDDEN FIELDS --}}
                <input type="hidden" value="{{ IDGenerator::generateID() }}" name="id">
                <input type="hidden" value="{{ $serviceAccount->id }}" name="ServiceAccountId">

                <div class="row">
                    @include('billing_meters.fields')
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection