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
                    <p class="m-0"><strong><span class="badge-lg bg-warning">Step 1</span></strong>Spanning</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/" class="text-muted">Home</a></li>
                        <li class="breadcrumb-item active"><a class="btn btn-success btn-sm" href="{{ route('serviceConnections.spanning-assigning', [$serviceConnection->id]) }}">Spanning</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('serviceConnections.bom-assigning', [$serviceConnection->id]) }}" class="text-muted">Bill of Materials</a></li>
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
            <b >Account: </b><span id="scId"><a href="{{ route('serviceConnections.show', [$serviceConnection->id]) }}">{{ $serviceConnection->id }}</a></span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col-md-6 col-lg-4">
            {{-- LINE --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        Line
                    </span>

                    <div class="card-tools">
                        @if ($spanningData != null)
                            <button id="clear-span-data" class="btn text-danger btn-tools btn-sm" title="Clear span data"><i class="fas fa-trash"></i></button>
                        @endif                        
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm">
                        <tr>
                            <th></th>
                            <th width="25%">Span</th>
                            <th width="25%">Size</th>
                            <th width="25%">Type</th>
                        </tr>
                        <tr>
                            <td>Primary</td>
                            <td>
                                <input type="number" step=".00000001" id="primaryKms" class="form-control" placeholder="in kilometers" value="{{ $spanningData==null ? '' : $spanningData->PrimarySpan }}">
                            </td>
                            <td>
                                <select id="primarySize" class="form-control">
                                    <option value="6" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "6" ? 'selected' : '') }}>6</option>
                                    <option value="4" {{  $spanningData==null ? '' : ($spanningData->PrimarySize == "4" ? 'selected' : '') }}>4</option>
                                    <option value="2" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "2" ? 'selected' : '') }}>2</option>
                                    <option value="1/0" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "1/0" ? 'selected' : '') }}>1/0</option>
                                    <option value="2/0" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "2/0" ? 'selected' : '') }}>2/0</option>
                                    <option value="3/0" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "3/0" ? 'selected' : '') }}>3/0</option>
                                    <option value="4/0" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "4/0" ? 'selected' : '') }}>4/0</option>
                                    <option value="266.8" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "266.8" ? 'selected' : '') }}>266.8</option>
                                    <option value="336.4" {{ $spanningData==null ? '' :  ($spanningData->PrimarySize == "336.4" ? 'selected' : '') }}>336.4</option>
                                </select>
                            </td>
                            <td>
                                <select id="primaryType" class="form-control">
                                    <option value="Bare" {{ $spanningData==null ? '' :  ($spanningData->PrimaryType == "Bare" ? 'selected' : '') }}>Bare</option>
                                    <option value="Insulated" {{ $spanningData==null ? '' :  ($spanningData->PrimaryType == "Insulated" ? 'selected' : '') }}>Insulated</option>
                                    <option value="Covered" {{ $spanningData==null ? '' :  ($spanningData->PrimaryType == "Covered" ? 'selected' : '') }}>Covered</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Neutral</td>
                            <td>
                                <input type="number" step=".00000001" id="neutralKms" class="form-control" placeholder="in kilometers" value="{{ $spanningData==null ? '' :  $spanningData->NeutralSpan }}">
                            </td>
                            <td>
                                <select id="neutralSize" class="form-control">
                                    <option value="6" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "6" ? 'selected' : '') }}>6</option>
                                    <option value="4" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "4" ? 'selected' : '') }}>4</option>
                                    <option value="2" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "2" ? 'selected' : '') }}>2</option>
                                    <option value="1/0" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "1/0" ? 'selected' : '') }}>1/0</option>
                                    <option value="2/0" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "2/0" ? 'selected' : '') }}>2/0</option>
                                    <option value="3/0" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "3/0" ? 'selected' : '') }}>3/0</option>
                                    <option value="4/0" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "4/0" ? 'selected' : '') }}>4/0</option>
                                    <option value="266.8" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "266.8" ? 'selected' : '') }}>266.8</option>
                                    <option value="336.4" {{ $spanningData==null ? '' :  ($spanningData->NeutralSize == "336.4" ? 'selected' : '') }}>336.4</option>
                                </select>
                            </td>
                            <td>
                                <select id="neutralType" class="form-control">
                                    <option value="Bare" {{ $spanningData==null ? '' :  ($spanningData->NeutralType == "Bare" ? 'selected' : '') }}>Bare</option>
                                    <option value="Insulated" {{ $spanningData==null ? '' :  ($spanningData->NeutralType == "Insulated" ? 'selected' : '') }}>Insulated</option>
                                    <option value="Covered" {{ $spanningData==null ? '' :  ($spanningData->NeutralType == "Covered" ? 'selected' : '') }}>Covered</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Secondary</td>
                            <td>
                                <input type="number" step=".00000001" id="secondaryKms" class="form-control" placeholder="in kilometers" value="{{ $spanningData==null ? '' :  $spanningData->SecondarySpan }}">
                            </td>
                            <td>
                                <select id="secondarySize" class="form-control">
                                    <option value="6" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "6" ? 'selected' : '') }}>6</option>
                                    <option value="4" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "4" ? 'selected' : '') }}>4</option>
                                    <option value="2" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "2" ? 'selected' : '') }}>2</option>
                                    <option value="1/0" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "1/0" ? 'selected' : '') }}>1/0</option>
                                    <option value="2/0" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "2/0" ? 'selected' : '') }}>2/0</option>
                                    <option value="3/0" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "3/0" ? 'selected' : '') }}>3/0</option>
                                    <option value="4/0" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "4/0" ? 'selected' : '') }}>4/0</option>
                                    <option value="266.8" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "266.8" ? 'selected' : '') }}>266.8</option>
                                    <option value="336.4" {{ $spanningData==null ? '' :  ($spanningData->SecondarySize == "336.4" ? 'selected' : '') }}>336.4</option>
                                </select>
                            </td>
                            <td>
                                <select id="secondaryType" class="form-control">
                                    <option value="Bare" {{ $spanningData==null ? '' :  ($spanningData->SecondaryType == "Bare" ? 'selected' : '') }}>Bare</option>
                                    <option value="Insulated" {{ $spanningData==null ? '' :  ($spanningData->SecondaryType == "Insulated" ? 'selected' : '') }}>Insulated</option>
                                    <option value="Covered" {{ $spanningData==null ? '' : ($spanningData->SecondaryType == "Covered" ? 'selected' : '') }}>Covered</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <button id="lineApply" class="btn btn-sm btn-primary">Apply</button>
                </div>
            </div>

            {{-- SDW --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        Service Drop Wire
                    </span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm">
                        <tr>
                            <td>Span</td>
                            <td>
                                <input type="number" step=".00000001" id="sdwSpan" class="form-control" placeholder="in kilometers" value="{{ $spanningData==null ? '' :  $spanningData->SDWSpan }}">
                            </td>
                        </tr>
                        <tr>
                            <td>Size</td>
                            <td>
                                <select id="sdwSize" class="form-control">
                                    <option value="6" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "6" ? 'selected' : '') }}>6</option>
                                    <option value="4" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "4" ? 'selected' : '') }}>4</option>
                                    <option value="2" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "2" ? 'selected' : '') }}>2</option>
                                    <option value="1/0" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "1/0" ? 'selected' : '') }}>1/0</option>
                                    <option value="2/0" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "2/0" ? 'selected' : '') }}>2/0</option>
                                    <option value="3/0" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "3/0" ? 'selected' : '') }}>3/0</option>
                                    <option value="4/0" {{ $spanningData==null ? '' :  ($spanningData->SDWSize == "4/0" ? 'selected' : '') }}>4/0</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>
                                <select id="sdwType" class="form-control">
                                    <option value="Duplex" {{ $spanningData==null ? '' :  ($spanningData->SDWType == "Duplex" ? 'selected' : '') }}>Duplex</option>
                                    <option value="Triplex" {{ $spanningData==null ? '' :  ($spanningData->SDWType == "Triplex" ? 'selected' : '') }}>Triplex</option>
                                    <option value="Quadruplex" {{ $spanningData==null ? '' :  ($spanningData->SDWType == "Quadruplex" ? 'selected' : '') }}>Quadruplex</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <button id="sdwApply" class="btn btn-sm btn-primary">Apply</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <span class="card-title">Materials Assigned</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm" id="materials-table">
                        <thead>
                            <th>NEA Code</th>
                            <th>Description</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Project Requirements</th>
                            <th class="text-right">Extended Cost</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('serviceConnections.bom-assigning', [$serviceConnection->id]) }}" class="btn btn-primary">Next <i class="fas fa-arrow-alt-circle-right" style="margin-left: 5px;"></i></a>
                    <i class="text-muted" style="margin-left: 15px;">Bill of Materials Assigning</i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script type="text/javascript">
        var lines = [];
        $(document).ready(function() {
            fetchMaterials();

            $('#lineApply').on('click', function(e) {
                assessLines();
                
                if (lines.length > 0) {
                    $.ajax({
                        url : '/bill_of_materials_matrices/insert-spanning-materials',
                        type : 'POST',
                        data : {
                            _token : "{{ csrf_token() }}",
                            data : lines,
                        },
                        success : function(response) {
                            fetchMaterials();
                        },
                        error : function(error) {
                            console.log(error);
                            // location.reload();
                        }
                    });
                } else {
                    alert('No line data provided!');
                }                
            });

            $('#sdwApply').on('click', function(e) {
                $.ajax({
                    url : '/bill_of_materials_matrices/insert-sdw-materials',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        ServiceConnectionId : $('#scId').text(),
                        Span : $('#sdwSpan').val(),
                        Size : $('#sdwSize').val(),
                        Type : $('#sdwType').val(),
                    },
                    success : function(response) {
                        fetchMaterials();
                    },
                    error : function(error) {
                        console.log(error);
                        // location.reload();
                    }
                });
            });

            $('#clear-span-data').on('click', function(e) {
                if (confirm('Are you sure you want to clear spanning data?')) {
                    $.ajax({
                        url : '/spanningDatas/{{  $spanningData==null ? '' : $spanningData->id }}',
                        type : 'DELETE',
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : "{{  $spanningData==null ? '' : $spanningData->id }}"
                        },
                        success : function(response) {
                            location.reload();
                        },
                        error : function(error) {
                            alert('Error deleting spanning data! See console for details.');
                            console.log(error);
                        }
                    });
                }
            });
        });

        function assessLines() {
            lines = [];
            if (!jQuery.isEmptyObject($('#primaryKms').val())) {
                lines.push({
                    line : 'primary',
                    span : $('#primaryKms').val(),
                    size : $('#primarySize').val(),
                    type : $('#primaryType').val(),
                    svcId : $('#scId').text(),
                });
            }

            if (!jQuery.isEmptyObject($('#neutralKms').val())) {
                lines.push({
                    line : 'neutral',
                    span : $('#neutralKms').val(),
                    size : $('#neutralSize').val(),
                    type : $('#neutralType').val(),
                    svcId : $('#scId').text(),
                });
            }

            if (!jQuery.isEmptyObject($('#secondaryKms').val())) {
                lines.push({
                    line : 'secondary',
                    span : $('#secondaryKms').val(),
                    size : $('#secondarySize').val(),
                    type : $('#secondaryType').val(),
                    svcId : $('#scId').text(),
                });
            }
        }

        function fetchMaterials() {
            $.ajax({
                url : '/bill_of_materials_matrices/fetch-span-material/',
                type : 'GET',
                data : {
                    scId : $('#scId').text(),
                },
                success : function(response) {

                    $('#materials-table tbody tr').remove();

                    var data = JSON.parse(response);

                    $.each(data, function(index, element) {
                        $('#materials-table tbody').append(
                            '<tr>' + 
                                '<td>' + data[index]['id'] + '</td>' + 
                                '<td>' + data[index]['Description'] + '</td>' +
                                '<td class="text-right">' + Number(parseFloat(data[index]['Amount']).toFixed(2)).toLocaleString() + '</td>' +
                                '<td class="text-right">' + data[index]['ProjectRequirements'] + '</td>' + 
                                '<td class="text-right">' + Number(parseFloat(data[index]['ExtendedCost']).toFixed(2)).toLocaleString() + '</td>' +
                                    // '<td class="text-right">' +
                                    //     '<button title=\'Delete ' + data[index]['Description'] + '\' id="delete-' + data[index]['id'] + '" onclick="deleteMaterial(\'' + data[index]['id'] + '\')" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>' +
                                    // '</td>' +
                            '</tr>'
                        );
                    });
                },
                error : function(error) {
                    console.log(console.error());
                    // location.reload();
                }
            }); 
        }

        function deleteMaterial(neacode) {
            if (confirm('Are you sure you want to delete this material?')) {
                $.ajax({
                    url : '/bill_of_materials_matrices/delete-span-material/',
                    type: "GET",
                    data: {
                        ServiceConnectionId: $('#scId').text(),
                        MaterialsId : neacode
                    },
                    success : function(data) {
                        fetchMaterials();
                    },
                    error : function(error) {
                        console.log(error);
                        location.reload();
                    }
                });  
            }
        }
    </script>
@endpush