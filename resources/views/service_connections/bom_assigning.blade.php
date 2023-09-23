@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <p class="m-0"><strong><span class="badge-lg bg-warning">Step 2</span></strong>Bill of Materials Assigning</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active"><a class="text-muted" href="{{ route('serviceConnections.spanning-assigning', [$serviceConnection->id]) }}">Spanning</a></li>
                        <li class="breadcrumb-item"><a class="btn btn-success btn-sm" href="{{ route('serviceConnections.bom-assigning', [$serviceConnection->id]) }}" class="text-muted">Bill of Materials</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('serviceConnections.transformer-assigning', [$serviceConnection->id]) }}" class="text-muted">Transformer</a></li>
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
            <b >Account: </b><span id="scId">{{ $serviceConnection->id }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                                {{-- <input class="form-control" id="autosuggest" placeholder="Type a structure"> --}}
                                <select class="form-control select2" style="width: 100%;" name="structures" id="structures">
                                    @foreach ($structures as $item)
                                        <option value="{{ $item->Data }}">{{ $item->Data }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="number" id="qty"  placeholder="Quantity">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-info" id="add-structure"><i class="fas fa-check-circle"></i></button>
                            </div>
                        </div>
                    </span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm" id="strucures-table">
                        <thead>
                            <th>Structure</th>
                            <th width="30%">Project Requirement Quantity</th>
                            <th width="10%"></th>
                        </thead>
                        <tbody>
                            @foreach ($structuresAssigned as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ $item->StructureId }}</td>
                                    <td>{{ $item->Quantity }}</td>
                                    <td>
                                        <button onclick="deleteStructure('{{ $item->id }}')" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="card-footer">
                    <a href="{{ route('billOfMaterialsMatrices.view', [$serviceConnection->id]) }}" class="btn btn-primary">Submit</a>
                </div> --}}
            </div>
        </div>

        <div class="col-md-8 col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <span class="card-title">Materials Assigned</span>

                    <div class="card-tools">
                        <button class="btn btn-tool text-success" title="Add Custom Material"  data-toggle="modal" data-target="#modal-default"><i class="fas fa-plus"></i></button>
                        <button class="btn btn-tool" title="Print"><i class="fas fa-print"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm" id="materials-table">
                        <thead>
                            <th>NEA Code</th>
                            <th>Description</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Project Requirements</th>
                            <th class="text-right">Extended Cost</th>
                            <th width="8%" class="text-center"></th>
                        </thead>
                        <tbody>
                            @php
                                $total = 0.0;
                            @endphp
                            @foreach ($billOfMaterials as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->Description }}</td>
                                    <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                    <td class="text-right">{{ $item->ProjectRequirements }}</td>
                                    <td class="text-right">{{ number_format($item->ExtendedCost, 2) }}</td>
                                    <td class="text-right">
                                        <button title="Delete {{ $item->Description }}" id="delete-{{ $item->id }}" onclick="deleteMaterial('{{ $item->id }}')" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                @php
                                    $total += doubleval($item->ExtendedCost);
                                @endphp
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><strong>{{ number_format($total, 2) }}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('serviceConnections.forward-to-transformer-assigning', [$serviceConnection->id]) }}" class="btn btn-primary">Next <i class="fas fa-arrow-alt-circle-right" style="margin-left: 5px;"></i></a>
                    <i class="text-muted" style="margin-left: 15px;">Transformer Assigning</i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR ADDING MATERIALS --}}
<div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Custom Material</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="materials-data">
                    <input type="hidden" name="_token" id="csrfMaterial" value="{{Session::token()}}">

                    <input type="hidden" class="form-control" name="ServiceConnectionId" id="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                    <div class="form-group">
                        <label>New Material</label>
                        <select class="form-control select2" style="width: 100%;" name="MaterialsId" id="MaterialsId">
                            @foreach ($materials as $item)
                                <option value="{{ $item->id }}">{{ $item->Description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" name="Quantity" id="Quantity" value="1" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="submit">Save changes</button> --}}
                <input type="submit" value="Add" id="submit" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script type="text/javascript">

    $(document).ready(function() {        

        $('#add-structure').on('click', function() {
            if (jQuery.isEmptyObject($('#qty').val())) {
                alert('Please fill in the fields to continue!');
            } else {
                $.ajax({
                    url : '/structure_assignments/insert-structure-assignment',
                    type: "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        ServiceConnectionId : $('#scId').text(),
                        Structure : $('#structures').val(),
                        Quantity : $('#qty').val(),
                    },
                    success : function(response) {
                        var data = JSON.parse(response);
                        $('#strucures-table tbody').append('<tr id="' + data['id'] + '">'+
                                                                '<td>' + data['StructureId'] + '</td>'+
                                                                '<td>' + data['Quantity'] + '</td>'+
                                                                '<td>' +
                                                                    '<button onclick="deleteStructure(' + data["id"] + ')" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>' +
                                                                '</td>' +
                                                            '</tr>'); 

                        fetchAllMaterials($('#scId').text());
                        // location.reload();
                    },
                    error : function(error) {
                        console.log(error);
                        alert('Error inserting material! Contact tech support for assistance.')
                    }
                });  
            }
                      
        });
    });  

    function fetchAllMaterials(scId) {
        // SHOW TO MATERIALS TABLE
        $.ajax({
            url : '/bill_of_materials_matrices/get-bill-of-materials-json/',
            type : 'GET',
            data : {
                scId : scId,
            },
            success : function(response) {

                $('#materials-table tbody tr').remove();

                var data = JSON.parse(response);
                var total = 0.0;

                $.each(data, function(index, element) {
                    $('#materials-table tbody').append(
                        '<tr>' + 
                            '<td>' + data[index]['id'] + '</td>' + 
                            '<td>' + data[index]['Description'] + '</td>' +
                            '<td class="text-right">' + Number(parseFloat(data[index]['Amount']).toFixed(2)).toLocaleString() + '</td>' +
                            '<td class="text-right">' + data[index]['ProjectRequirements'] + '</td>' + 
                            '<td class="text-right">' + Number(parseFloat(data[index]['ExtendedCost']).toFixed(2)).toLocaleString() + '</td>' +
                            '<td class="text-right">' +
                                '<button title=\'Delete ' + data[index]['Description'] + '\' id="delete-' + data[index]['id'] + '" onclick="deleteMaterial(\'' + data[index]['id'] + '\')" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>' +
                            '</td>' +
                        '</tr>'
                    );
                    total += parseFloat(data[index]['ExtendedCost']);
                });

                $('#materials-table tbody').append(
                    '<tr>' +
                        '<td><strong>Total</strong></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td class="text-right"><strong>' + Number(total.toFixed(2)).toLocaleString() + '</strong></td>' +
                        '<td></td>' +
                    '</tr>'
                );
            },
            error : function(error) {
                console.log(console.error());
                location.reload();
            }
        }); 
    }
 
    function deleteStructure(id) {
        if (confirm('Are you sure you want to delete this structure?')) {
            $.ajax({
                url : '/structureAssignments/' + id,
                type: "DELETE",
                data: {
                    _token:'{{ csrf_token() }}',
                    id: id,
                },
                success : function(data) {
                    fetchAllMaterials($('#scId').text());
                },
                error : function(error) {
                    console.log(error);
                    location.reload();
                    // alert("Error deleting structure " + error);
                }
            });  
            $('#' + id).remove();
        }
    } 

    function deleteMaterial(neacode) {
        if (confirm('Are you sure you want to delete this material?')) {
            $.ajax({
                url : '/bill_of_materials_matrices/delete-material/',
                type: "GET",
                data: {
                    ServiceConnectionId: $('#scId').text(),
                    MaterialsId : neacode
                },
                success : function(data) {
                    fetchAllMaterials($('#scId').text());
                },
                error : function(error) {
                    console.log(error);
                    location.reload();
                    // alert("Error deleting structure " + error);
                }
            });  
        }
    }

    $('#submit').on('click', function(e) {
        e.preventDefault();
        var scId = $('#ServiceConnectionId').val();
        var neaCode = $('#MaterialsId').val();
        var qty = $('#Quantity').val();

        $.ajax({
            url : '/bill_of_materials_matrices/add-custom-material',
            type: "POST",
            data : {
                _token : $('#csrfMaterial').val(),
                ServiceConnectionId : scId,
                MaterialsId : neaCode,
                Quantity : qty
            },
            success : function(response) {
                $('#materials-data').trigger('reset');
                $('#modal-default').modal('hide'); 
                fetchAllMaterials(scId);               
            },
            error : function(error) {
                $('#materials-data').trigger('reset');
                $('#modal-default').modal('hide'); 
                alert("Error adding structures to row! Contact support immediately.");
            }
        });
    });
    </script>
@endpush