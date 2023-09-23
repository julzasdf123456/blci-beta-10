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
                    <i class="text-muted">Step 3. Import transformer details</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- INFO CARDS --}}
    <div class="col-lg-3 col-md-4">
        {{-- CONSUMER INFO --}}
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

        {{-- METER INFO --}}
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Meter Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                @if ($meters != null)
                    <table class="table table-hover table-borderless table-sm">
                        <tr>
                            <td>Brand</td>
                            <td>{{ $meters->Brand }}</td>
                        </tr>
                        <tr>
                            <td>Serial No</td>
                            <td>{{ $meters->SerialNumber }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $meters->Status }}</td>
                        </tr>
                        <tr>
                            <td>Multiplier</td>
                            <td>{{ $meters->Multiplier }}</td>
                        </tr>
                        <tr>
                            <td>Connection Date</td>
                            <td>{{ date('F d, Y', strtotime($meters->ConnectionDate)) }}</td>
                        </tr>
                    </table>                    
                @endif
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="col-lg-9 col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><strong>Step 3. </strong>Transformer Information</span>
            </div>
            {!! Form::open(['route' => 'billingTransformers.store']) !!}
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="ServiceAccountId" value="{{ $serviceAccount->id }}">

                    @include('billing_transformers.fields')
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Finish', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection