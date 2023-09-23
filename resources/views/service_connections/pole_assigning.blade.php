@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')
{{-- 
    READ ME
    ________________________________________
    FOR POLE
        - The 'BillOfMaterialsIndex.StructureType' should be set to 'POLE'
    
    --}}
@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <p class="m-0"><strong><span class="badge-lg bg-warning">Step 4</span></strong>Pole Assigning</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active"><a class="text-muted" href="{{ route('serviceConnections.spanning-assigning', [$serviceConnection->id]) }}">Spanning</a></li>
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('serviceConnections.bom-assigning', [$serviceConnection->id]) }}" class="text-muted">Bill of Materials</a></li>
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('serviceConnections.transformer-assigning', [$serviceConnection->id]) }}" class="text-muted">Transformer</a></li>
                        <li class="breadcrumb-item"><a class="btn btn-success btn-sm" href="{{ route('serviceConnections.pole-assigning', [$serviceConnection->id]) }}" class="text-muted">Pole</a></li>
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('serviceConnections.metering-equipment-assigning', [$serviceConnection->id]) }}" class="text-muted">Special Equipment</a></li>
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
            {{-- POLES SELECT --}}
            <div class="card">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Wood</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Steel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Concrete</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">

                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                            <table class="table table-sm">
                                <thead>
                                    <th>Description</th>
                                    <th width="18%">Quantity</th>
                                    <th width="10%">Add</th>
                                </thead>
                                <tbody>
                                    @foreach ($poleIndex as $item)
                                        @if ($item->Type == 'Wood')
                                            <tr>
                                                <td id="code-{{ $item->IndexId }}" data-id="{{ $item->id }}">{{ $item->Description }}</td>
                                                <td>
                                                    <input id="qty-{{ $item->IndexId }}" type="number" class="form-control">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm text-success" onclick="addPole({{ $item->IndexId }})"><i class="fas fa-plus-circle"></i></button>
                                                </td>
                                            </tr>
                                        @endif                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                            <table class="table table-sm">
                                <thead>
                                    <th>Description</th>
                                    <th width="18%">Quantity</th>
                                    <th width="10%">Add</th>
                                </thead>
                                <tbody>
                                    @foreach ($poleIndex as $item)
                                        @if ($item->Type == 'Steel')
                                            <tr>
                                                <td id="code-{{ $item->IndexId }}" data-id="{{ $item->id }}">{{ $item->Description }}</td>
                                                <td>
                                                    <input id="qty-{{ $item->IndexId }}" type="number" class="form-control">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm text-success" onclick="addPole({{ $item->IndexId }})"><i class="fas fa-plus-circle"></i></button>
                                                </td>
                                            </tr>
                                        @endif  
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                            <table class="table table-sm">
                                <thead>
                                    <th>Description</th>
                                    <th width="18%">Quantity</th>
                                    <th width="10%">Add</th>
                                </thead>
                                <tbody>
                                    @foreach ($poleIndex as $item)
                                        @if ($item->Type == 'Concrete')
                                            <tr>
                                                <td id="code-{{ $item->IndexId }}" data-id="{{ $item->id }}">{{ $item->Description }}</td>
                                                <td>
                                                    <input id="qty-{{ $item->IndexId }}" type="number" class="form-control">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm text-success" onclick="addPole({{ $item->IndexId }})"><i class="fas fa-plus-circle"></i></button>
                                                </td>
                                            </tr>
                                        @endif  
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6 col-lg-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Assigned Poles</span>
                </div>
                <div class="card-body table-responsive p-0">
                    {{-- TRANSFORMERS TABLE --}}
                    <table class="table table-sm" id="poles-table">
                        <thead>
                            <th>Poles</th>
                            <th>Quantity</th>
                            <th width="10%" class="text-center">Amount</th>
                            <th width="10%" class="text-center">Cost</th>
                            <th width="9%"></th>
                        </thead>
                        <tbody>
                            @foreach ($poleAssigned as $item)
                                <tr>
                                    <td>{{ $item->Description }}</td>
                                    <td>{{ $item->Quantity }}</td>
                                    <td class="text-right">{{ number_format($item->Amount) }}</td>
                                    <td class="text-right">{{ number_format(floatval($item->Quantity) * floatval($item->Amount), 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm text-danger" onclick="deletePole('{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{-- <a href="{{ route('serviceConnections.quotation-summary', [$serviceConnection->id]) }}" class="btn btn-primary">Next <i class="fas fa-arrow-alt-circle-right" style="margin-left: 5px;"></i></a> --}}
                    <a href="{{ route('serviceConnections.metering-equipment-assigning', [$serviceConnection->id]) }}" class="btn btn-primary">Next <i class="fas fa-arrow-alt-circle-right" style="margin-left: 5px;"></i></a>
                    {{-- <i class="text-muted" style="margin-left: 15px;">Finalize Quotation and BoM</i> --}}
                    <i class="text-muted" style="margin-left: 15px;">Special Equipment Assigning</i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script type="text/javascript">
        function addPole(id) {
            var neacode = $('#code-' + id).attr("data-id");
            var qty = $('#qty-' + id).val();
            if (jQuery.isEmptyObject(qty)) {
                alert('Please specify a quantity');
            } else {
                $.ajax({
                    url : '/bill_of_materials_matrices/insert-pole',
                    type : 'POST',
                    data : {
                        _token : $('#csrf').val(),
                        ServiceConnectionId : $('#scId').text(),
                        MaterialsId : neacode,
                        Quantity : qty,
                    },
                    success : function(response) {
                        location.reload()
                        // fetchPoles()
                    },
                    error : function(error) {
                        console.log(error);
                    }
                });
            }
        }

        function fetchPoles() {            
            $.ajax({
                url : '/bill_of_materials_matrices/fetch-poles/',
                type : 'GET',
                data : {
                    ServiceConnectionId : $('#scId').text(),
                },
                success : function(response) {
                    $('#poles-table tbody tr').remove();

                    var data = JSON.parse(response);
                    $.each(data, function(index, element) {
                        $('#poles-table tbody').append(
                            '<tr>' +
                                '<td>' + data[index]['Description'] + '</td>' +
                                '<td>' + data[index]['Quantity'] + '</td>' +
                                '<td class="text-right">' + Number(parseFloat(data[index]['Amount']).toFixed(2)).toLocaleString() + '</td>' +
                                '<td class="text-right">' + Number((parseInt(data[index]['Quantity']) * parseFloat(data[index]['Amount'])).toFixed(2)).toLocaleString() + '</td>' +
                                '<td>' +
                                    '<button class="btn btn-sm text-danger" onclick=deletePole(' + data[index]['id'] + ')><i class="fas fa-trash"></i></button>' +
                                '</td>' +
                            '</tr>'
                        );
                    });
                    
                },
                error : function(response) {
                    console.log(response);
                }
            });
        }

        function deletePole(id) {
            if (confirm('Are you sure you want to delete this pole?')) {
                $.ajax({
                    url : '/bill_of_materials_matrices/delete-pole/',
                    type : 'GET',
                    data : {
                        id : id,
                    },
                    success : function(response) {
                        fetchPoles();
                    },
                    error : function(response) {
                        console.log(response);
                    }
                });
            }
            
        }
    </script>
@endpush