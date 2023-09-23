@php
    use App\Models\IDGenerator;
    use Illuminate\Support\Facades\Auth; 
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Katas Ng Vat Total</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'katasNgVatTotals.store']) !!}

            <div class="card-body">

                <div class="row">
                    <input type="hidden" name="id" id="id" value="{{ IDGenerator::generateIDandRandString() }}">

                    @include('katas_ng_vat_totals.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('katasNgVatTotals.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
