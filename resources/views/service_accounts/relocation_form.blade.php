@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Relocation Form</h4>
                    <i class="text-muted">Energized/executed relocation requests.</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Original Account Details</span>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Account Name</td>
                        <th>{{ $account != null ? $account->ServiceAccountName : '-' }}</th>
                    </tr>
                    <tr>
                        <td>Account Address</td>
                        <th>{{ ServiceAccounts::getAddress($account) }}</th>
                    </tr>
                    <tr>
                        <td>Account Number</td>
                        <th>{{ $account->OldAccountNo }}</th>
                    </tr>
                    <tr>
                        <td>Account ID</td>
                        <th>{{ $account->id }}</th>
                    </tr>
                    <tr>
                        <td>Account Type</td>
                        <th>{{ $account->AccountType }}</th>
                    </tr>
                    <tr>
                        <td>Area Code/Route</td>
                        <th>{{ $account->AreaCode }}</th>
                    </tr>
                    <tr>
                        <td>Sequence Number</td>
                        <th>{{ $account->SequenceCode }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            {!! Form::open(['route' => 'serviceAccounts.store-relocation']) !!}
            <div class="card-header bg-primary">
                <span class="card-title">Relocation Validation</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="AccountNumber" value="{{ $account != null ? $account->id : '' }}">
                    <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection!=null ? $serviceConnection->id : '' }}">

                    <!-- Purok Field -->
                    <div class="col-md-4">
                        {!! Form::label('Purok', 'Purok:') !!}
                        {!! Form::text('Purok', $serviceConnection!=null ? $serviceConnection->Sitio : '', ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
                    </div>

                    <!-- Barangay Field -->
                    <div class="col-md-4">
                        {!! Form::label('Barangay', 'Barangay:') !!}
                        {!! Form::select('Barangay', $barangays, $serviceConnection!=null ? $serviceConnection->Barangay : '', ['class' => 'form-control', 'id' => 'BarangaySA']) !!}
                    </div>

                    <!-- Town Field -->
                    <div class="col-md-4">
                        {!! Form::label('Town', 'Town:') !!}
                        {!! Form::select('Town', $town, $serviceConnection!=null ? $serviceConnection->Town : '', ['class' => 'form-control', 'id' => 'TownSA']) !!}
                    </div>

                    <!-- Route Field -->
                    <div class="col-md-4">
                        {!! Form::label('AreaCode', 'New Area Code:') !!}
                        {!! Form::number('AreaCode', $account != null ? $account->AreaCode : '', ['class' => 'form-control', 'maxlength' => 5]) !!}
                    </div>

                    <!-- Sequence Field -->
                    <div class="col-md-4">
                        {!! Form::label('SequenceCode', 'New Sequence Code:') !!}
                        {!! Form::number('SequenceCode', $account != null ? $account->SequenceCode : '', ['class' => 'form-control', 'maxlength' => 5]) !!}
                    </div>

                    <!-- Meter Reader Field -->
                    <div class="col-md-4">
                        {!! Form::label('MeterReader', 'Meter Reader:') !!}
                        <select class="custom-select select2"  name="MeterReader">
                            <option value="">n/a</option>
                            @foreach ($meterReaders as $items)
                                <option value="{{ $items->id }}">{{ $items->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- GROUP FIELD --}}
                    <div class="col-md-4">
                        {!! Form::label('GroupCode', 'Group:') !!}
                        <select name="GroupCode" class="form-control">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Confirm Relocation', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection