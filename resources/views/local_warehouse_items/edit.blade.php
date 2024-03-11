@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Edit Local Warehouse Items
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($localWarehouseItems, ['route' => ['localWarehouseItems.update', $localWarehouseItems->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('local_warehouse_items.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('localWarehouseItems.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
