@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Special Equipment Materials</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">
                            Special Equipment Indices
                        </span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover">
                            <thead>
                                <th>NEA Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th width="8%"></th>
                            </thead>
                            <tbody>
                                @foreach ($specialEquipmentMaterials as $item)
                                    <tr>
                                        <td>{{ $item->NEACode }}</td>
                                        <td>{{ $item->Description }}</td>
                                        <td>{{ $item->Amount }}</td>
                                        <td class="text-right">
                                            {!! Form::open(['route' => ['specialEquipmentMaterials.destroy', $item->id], 'method' => 'delete']) !!}
                                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <span class="card-title">
                            Add Materials to Special Equipment Index
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="materials">Select Material Asset</label>
                            <select class="form-control select2" style="width: 100%;" id="materials">
                                @foreach ($materialAssets as $item)
                                    <option value="{{ $item->id }}">{{ $item->Description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-info" id="add-material">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#add-material').on('click', function() {
                $.ajax({
                    url : '/special_equipment_materials/create-material',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        NEACode : $('#materials').val(),
                    },
                    success : function(res) {
                        if (res['response'] == 'ok') {
                            location.reload();
                        } else {
                            alert("Error adding material. Contact support for more!");
                        }
                    },
                    error : function(err) {
                        alert("Error adding material. Contact support for more!");
                    }
                })
            })
        })
    </script>
@endpush

