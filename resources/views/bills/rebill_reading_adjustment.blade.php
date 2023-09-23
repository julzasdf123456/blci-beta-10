@php
    use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Reading Adjustment Console</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    {!! Form::model($reading, ['route' => ['bills.rebill', $reading->id], 'method' => 'post']) !!}
                    <div class="card-header">
                        <span class="card-title">Adjust Reading</span>
                    </div>
                    <div class="card-body">
                        <!-- Kwhused Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('KwhUsed', 'Reading:') !!}
                            {!! Form::text('KwhUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
                        </div>

                        {{-- HIDDEN FIELDS --}}
                        <input type="hidden" name="MeterReader" value="{{ Auth::id() }}">
                        <input type="hidden" name="Notes" value="READING RE-ADJUSTED">

                        @if ($previousBill != null)
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Account Number: </td>
                                                <th>{{ $previousBill->AccountNumber }}</th>
                                            </tr>
                                            <tr>
                                                <td>Service Period: </td>
                                                <th>{{ $reading->ServicePeriod == null ? 'n/a' : date('F Y', strtotime($reading->ServicePeriod)) }}</th>
                                            </tr>
                                            <tr>
                                                <td>Previous Reading: </td>
                                                <th>{{ $previousBill->PresentKwh }}</th>
                                            </tr>
                                            <tr>
                                                <td>Previous KwH Used: </td>
                                                <th>{{ $previousBill->KwhUsed }}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="card">
                                <div class="card-body">
                                    <p><i>No previous bill found!</i></p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        {!! Form::submit('Save and Re-Bill', ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection