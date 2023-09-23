@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')
{{-- 
    READ ME
    ________________________________________
    FOR THE TRANSFORMER
        - There are two types of Transformer materials in the 'TransformerAssignedMatrix': 
            1. Transformer - the transformer material itself
            2. Fuse - the fuse link
        - Make sure that in the Transformer index ('TransformerIndex'), you specify the FuseLinkCode if there are any

    FOR THE BRACKETS
        - Make sure that the 'StructureAssignments.Type' and 'BillOfMaterialMatrix.StructureType' is set to 'A_DT'
    
    --}}
@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <p class="m-0"><strong><span class="badge-lg bg-warning">Step 3</span></strong>Transformer Assigning</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active"><a class="text-muted" href="{{ route('serviceConnections.spanning-assigning', [$serviceConnection->id]) }}">Spanning</a></li>
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('serviceConnections.bom-assigning', [$serviceConnection->id]) }}" class="text-muted">Bill of Materials</a></li>
                        <li class="breadcrumb-item"><a class="btn btn-success btn-sm" href="{{ route('serviceConnections.transformer-assigning', [$serviceConnection->id]) }}" class="text-muted">Transformer</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('serviceConnections.pole-assigning', [$serviceConnection->id]) }}" class="text-muted">Pole</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <address>
                <strong>{{ $serviceConnection->ServiceAccountName }}</strong><br>
                {{ ServiceConnections::getAddress($serviceConnection) }}<br>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            
        </div>
        <div class="col-sm-4 invoice-col">
            <br>
            <b >Account: </b><span id="scId"><a href="{{ route('serviceConnections.show', [$serviceConnection->id]) }}">{{ $serviceConnection->id }}</a></span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col-md-6 col-lg-5">
            {{-- TRANSFORMER SELECT --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Choose Transformer</span>

                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>           
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                    <table class="table table-sm">
                        <thead>
                            <th>Description</th>
                            <th width="18%">Quantity</th>
                            <th width="10%">Add</th>
                        </thead>
                        <tbody>
                            @foreach ($transformerIndex as $item)
                                <tr>
                                    <td id="code-{{ $item->IndexId }}" data-id="{{ $item->id }}">{{ $item->Description }}</td>
                                    <td>
                                        <input id="qty-{{ $item->IndexId }}" type="number" class="form-control">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm text-success"  data-card-widget="collapse" onclick="addTransformer({{ $item->IndexId }})"><i class="fas fa-plus-circle"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- BRACKET SELECT --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Choose Transformer Bracket and Extras</span>

                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>           
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                    <table class="table table-sm">
                        <thead>
                            <th>Bracket and Extras</th>
                            <th width="18%">Quantity</th>
                            <th width="10%">Add</th>
                        </thead>
                        <tbody>
                            @foreach ($structureBrackets as $item)
                                <tr>
                                    <td id="id-{{ $item->id }}" data-id="{{ $item->id }}">{{ $item->Data }}</td>
                                    <td><input type="number" id="brqty-{{ $item->id }}" class="form-control"></td>
                                    <td>
                                        <button class="btn btn-sm text-success" onclick="addBracket({{ $item->id }})"><i class="fas fa-plus-circle"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Assigned Transformer</span>
                    <div class="card-tools">
                        <a href="{{ route('serviceConnections.pole-assigning', [$serviceConnection->id]) }}">Consumer Has Transformer Already <i class="fas fa-info-circle"></i></a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    {{-- TRANSFORMERS TABLE --}}
                    <table class="table table-sm" id="transformers-table">
                        <thead>
                            <th>Transformer</th>
                            <th>Quantity</th>
                            <th class="text-center">Cost</th>
                            <th width="10%"></th>
                        </thead>
                        <tbody>
                            @foreach ($transformerMatrix as $item)
                                <tr>
                                    <td>{{ $item->Description }}</td>
                                    <td>{{ $item->Quantity }}</td>
                                    <td class="text-right">{{ number_format(floatval($item->Amount) * floatval($item->Quantity), 2) }}</td>
                                    <td class="text-right">
                                        <button class="btn btn-sm text-danger" onclick="deleteTransformer('{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="divider"></div>
                    <span style="margin-left: 20px; padding-left: 20px; padding-top: 8px; padding-bottom: 8px; border-left: 3px solid #0288d1;">
                        <strong>Bracket Type: </strong><span class="badge-lg bg-success" id="structure-baybeh"></span>
                    </span>
                    {{-- BRACKETS TABLE --}}
                    <table class="table table-sm" id="brackets-table">
                        <thead>
                            <th>NEA Code</th>
                            <th>Description</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Project Requirements</th>
                            <th class="text-right">Extended Cost</th>
                            <th width="5%">
                                <button class="btn btn-sm text-danger" onclick="clearBracket()" title="Clear Brackets"><i class="fas fa-trash"></i></button>
                            </th>
                        </thead>
                        <tbody>
                            @foreach ($bracketsAssigned as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->Description }}</td>
                                    <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                    <td class="text-right">{{ $item->ProjectRequirements }}</td>
                                    <td class="text-right">{{ number_format($item->ExtendedCost, 2) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('serviceConnections.pole-assigning', [$serviceConnection->id]) }}" class="btn btn-primary">Next <i class="fas fa-arrow-alt-circle-right" style="margin-left: 5px;"></i></a>
                    <i class="text-muted" style="margin-left: 15px;">Pole Assigning</i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            getBracketStructure();
        });

        function addTransformer(id) {
            var neacode = $('#code-' + id).attr("data-id");
            var qty = $('#qty-' + id).val();
            if (jQuery.isEmptyObject(qty)) {
                alert('Please specify a quantity');
            } else {
                $.ajax({
                    url : '/transformers_assigned_matrices/create-ajax',
                    type : 'POST',
                    data : {
                        _token : $('#csrf').val(),
                        TransformerId : id,
                        ServiceConnectionId : $('#scId').text(),
                        MaterialsId : neacode,
                        Quantity : qty,
                    },
                    success : function(response) {
                        location.reload()
                    },
                    error : function(error) {
                        console.log(error);
                    }
                })
            }
        }

        function deleteTransformer(id) {
            if (confirm('Are you sure you want to delete this transformer?')) {
                $.ajax({
                    url : '/transformersAssignedMatrices/' + id,
                    type: "DELETE",
                    data: {
                        _token:'{{ csrf_token() }}',
                        id: id,
                    },
                    success : function(data) {
                        location.reload()
                    },
                    error : function(error) {
                        console.log(error);
                        // alert("Error deleting structure " + error);
                    }
                });  
            }
        }

        function fetchTransformers() {            
            $.ajax({
                url : '/transformers_assigned_matrices/fetch-transformers/',
                type : 'GET',
                data : {
                    ServiceConnectionId : $('#scId').text(),
                },
                success : function(response) {
                    $('#transformers-table tbody tr').remove();

                    var data = JSON.parse(response);
                    $.each(data, function(index, element) {
                        $('#transformers-table tbody').append(
                            '<tr>' +
                                '<td>' + data[index]['Description'] + '</td>' +
                                '<td>' + data[index]['Quantity'] + '</td>' +
                                '<td class="text-right">' + Number((parseFloat(data[index]['Quantity']) * parseFloat(data[index]['Amount'])).toFixed(2)).toLocaleString() + '</td>' +
                                '<td class="text-right">' +
                                    '<button class="btn btn-sm text-danger" onclick="deleteTransformer("' + data[index]['id'] + '")"><i class="fas fa-trash"></i></button>' +
                                '</td>' +
                            '</tr>'
                        );
                    });                    
                },
                error : function(error) {
                    console.log(error);
                }
            });
        }

        function addBracket(id) {
            var qty = $('#brqty-' + id).val();
            if (jQuery.isEmptyObject(qty)) {
                alert('Please specify a quantity');
            } else {
                $.ajax({
                    url : '/bill_of_materials_matrices/insert-transformer-bracket',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        ServiceConnectionId : $('#scId').text(),
                        Quantity : qty,
                        StructureId : id,
                    },
                    success : function(response) {
                        fetchBrackets();
                    },
                    error : function(error) {
                        console.log(error);
                    }
                });
            }            
        }

        function fetchBrackets() {
            // SHOW TO MATERIALS TABLE
            $.ajax({
                url : '/bill_of_materials_matrices/get-bill-of-materials-brackets/',
                type : 'GET',
                data : {
                    scId : $('#scId').text(),
                },
                success : function(response) {

                    $('#brackets-table tbody tr').remove();

                    getBracketStructure();

                    var data = JSON.parse(response);

                    $.each(data, function(index, element) {
                        $('#brackets-table tbody').append(
                            '<tr>' + 
                                '<td>' + data[index]['id'] + '</td>' + 
                                '<td>' + data[index]['Description'] + '</td>' +
                                '<td class="text-right">' + Number(parseFloat(data[index]['Amount']).toFixed(2)).toLocaleString() + '</td>' +
                                '<td class="text-right">' + data[index]['ProjectRequirements'] + '</td>' + 
                                '<td class="text-right">' + Number(parseFloat(data[index]['ExtendedCost']).toFixed(2)).toLocaleString() + '</td>' +
                            '</tr>'
                        );
                    });

                },
                error : function(error) {
                    console.log(console.error());
                }
            }); 
        }

        function clearBracket() {
            if (confirm('Are you sure you want to clear the bracket materials for this transformer?')) {
                $.ajax({
                    url : '/structure_assignments/delete-brackets',
                    type : 'GET',
                    data : {
                        ServiceConnectionId : $('#scId').text(),
                    },
                    success : function(response) {
                        fetchBrackets();
                    },
                    error : function(error) {
                        console.log(console.error());
                    }
                });
            }            
        }

        function getBracketStructure() {
            $.ajax({
                url : '/structure_assignments/get-bracket-structure',
                type : 'GET',
                data : {
                    ServiceConnectionId : $('#scId').text(),
                },
                success : function(response) {
                    var data = JSON.parse(response);
                    $('#structure-baybeh').text(data['Structure']);
                },
                error : function(error) {
                    console.log(console.error());
                }
            });
        }
    </script>
@endpush