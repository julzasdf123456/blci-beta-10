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
                    <h4 style="display: inline; margin-right: 15px;">Update Account Wizzard</h4>
                    <i class="text-muted">Update transformer details</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Transformer Information</span>
            </div>
            {!! Form::model($billingTransformers, ['route' => ['billingTransformers.update', $billingTransformers->id], 'method' => 'patch']) !!}
            <div class="card-body">
                <div class="row">                    
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Transformernumber Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('TransformerNumber', 'Transformer Number:') !!}
                            </div>

                            <div class="col-lg-5 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('TransformerNumber', $billingTransformers!=null ? $billingTransformers->TransformerNumber : null, ['class' => 'form-control','maxlength' => 120,'maxlength' => 120]) !!}
                                </div>
                            </div>

                            <!-- Rating Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Rating', 'Rating (in KVA):') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Rating', $billingTransformers!=null ? $billingTransformers->Rating : null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Rentalfee Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('RentalFee', 'Rental Fee:') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('RentalFee', $billingTransformers!=null ? $billingTransformers->RentalFee : null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30, 'step' => 'any']) !!}
                                </div>
                            </div>

                            <!-- Load Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Load', 'Load:') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Load', $billingTransformers!=null ? $billingTransformers->Load : null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('serviceAccounts.show', [$billingTransformers->ServiceAccountId]) }}" class="btn btn-default">Cancel</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection