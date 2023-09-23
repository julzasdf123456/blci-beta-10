@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Structures Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('structures.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('structures.show_fields')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Materials in this Structure</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-valign-middle">
                    <thead>
                        <th>NEA Code</th>
                        <th>Description</th>
                        <th>Rate</th>
                        <th>Quantity</th>
                        <th width="10"></th>
                    </thead>
                    <tbody>
                        @foreach ($materials as $item)
                            <tr>
                                <td>{{ $item->NeaCode }}</td>
                                <td>{{ $item->Description }}</td>
                                <td>{{ $item->Rate }}</td>
                                <td>{{ $item->Quantity }}</td>
                                <td>
                                    {!! Form::open(['route' => ['materialsMatrices.destroy', $item->id], 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        <a href="{{ route('materialsMatrices.show', [$item->id]) }}"
                                        class='btn btn-default btn-xs'>
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a href="{{ route('materialsMatrices.edit', [$item->id]) }}"
                                        class='btn btn-default btn-xs'>
                                            <i class="far fa-edit"></i>
                                        </a>
                                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
