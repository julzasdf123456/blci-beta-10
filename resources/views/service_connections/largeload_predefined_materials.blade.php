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
                    <p class="m-0"><strong><span class="badge-lg bg-warning">Step 2</span></strong>Bill of Materials Assigning ({{ $serviceConnection->Options }})</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a class="btn btn-success btn-sm" href="{{ route('serviceConnections.bom-assigning', [$serviceConnection->id]) }}" class="text-muted">Bill of Materials</a></li>
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
            <address>
                <strong>Account:</strong> <span id="scId">{{ $serviceConnection->id }}<span><br>
                <strong>Application Type:</strong> <span id="scId">{{ $serviceConnection->AccountApplicationType == 'Temporary' ? $serviceConnection->AccountApplicationType . ' (' . $serviceConnection->TemporaryDurationInMonths . ' Months)' : $serviceConnection->AccountApplicationType }}<span>
            </address>
        </div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <span class="card-title">Materials Assigned</span>

                    <div class="card-tools">
                        <button class="btn btn-tool text-success" title="Add Custom Material"  data-toggle="modal" data-target="#modal-add"><i class="fas fa-plus"></i></button>
                        <a href="{{ route('preDefinedMaterialsMatrices.re-init', [$serviceConnection->id, $serviceConnection->Options]) }}" class="btn btn-tool text-info" title="Re-initialize all materials"><i class="fas fa-sync"></i></a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm" id="materials-table">
                        <thead>
                            <th>NEA Code</th>
                            <th>Description</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Project Requirements</th>
                            <th class="text-right">{{ $serviceConnection->AccountApplicationType=='Temporary' ? 'Rental Cost' : 'Extended Cost' }}</th>
                            <th class="text-right">Labor Cost</th>
                            <th class="text-center"></th>
                        </thead>
                        <tbody>
                            @php
                                $costTotal = 0;
                                $laborCostTotal = 0;
                            @endphp
                            @foreach ($preDef as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ $item->NEACode }}</td>
                                    <td id="description-{{ $item->id }}">{{ $item->Description }}</td>
                                    <td id="materialcost-{{ $item->id }}" class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                    <td id="quantity-{{ $item->id }}" class="text-right">{{ $item->Quantity }}</td>
                                    <td id="cost-{{ $item->id }}" class="text-right">{{ number_format($item->Cost, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->LaborCost, 2) }}</td>
                                    <td class="text-right">
                                        <span>
                                            <button id="edit" class="btn text-success btn-sm" labor-percentage="{{ $item->LaborPercentage }}" matid="{{ $item->id }}" style="display: inline;" data-toggle="modal" data-target="#modal-default" data-id="{{ $item->id }}" ><i class="fas fa-pen"></i></button>
                                            <button onclick="deleteMaterial('{{ $item->id }}')" id="delete-{{ $item->id }}" class="btn text-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </span>
                                    </td>
                                </tr>
                                @php
                                    $costTotal += floatval($item->Cost);
                                    $laborCostTotal += floatval($item->LaborCost);
                                @endphp
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">Sub-Total</td>
                                <td></td>
                                <th class="text-right">{{ number_format($costTotal, 2) }}</th>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">Labor Cost</td>
                                <td></td>
                                <th class="text-right">{{ number_format($laborCostTotal, 2) }}</th>
                                <td></td>
                            </tr>
                            @php
                                if ($serviceConnection->AccountApplicationType == 'Temporary' & $serviceConnection->Options == 'Underbuilt Only') {
                                    $others = floatval($laborCostTotal) * 0.3;
                                } elseif($serviceConnection->AccountApplicationType == 'Permanent' & $serviceConnection->Options == 'Transformer Only') {
                                    $others = floatval($laborCostTotal) * 0.3;
                                } else {
                                    $others = floatval($laborCostTotal) * 0.2;
                                }                                
                            @endphp
                            <tr title="Contingency, Engineering & Handling, etc.">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">Others</td>
                                <td></td>
                                <th class="text-right">{{ number_format($others, 2) }}</th>
                                <td></td>
                            </tr>
                            @php
                                $evat = ($others + $costTotal + $laborCostTotal) * .12;
                            @endphp
                            <tr title="Contingency, Engineering & Handling, etc.">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">12% E-VAT</td>
                                <td></td>
                                <th class="text-right">{{ number_format($evat, 2) }}</th>
                                <td></td>
                            </tr>
                            @php
                                $total = $others + $costTotal + $laborCostTotal + $evat;
                            @endphp
                            <tr title="Contingency, Engineering & Handling, etc.">
                                <td></td>
                                <td></td>
                                <td></td>
                                <th class="text-right">Overall Total</th>
                                <td></td>
                                <th class="text-right">{{ number_format($total, 2) }}</th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('serviceConnections.forward-to-verficaation', [$serviceConnection->id]) }}" class="btn btn-success">Finish <i class="fas fa-check-circle"></i></a> 
                    <i class="text-muted" style="margin-left: 15px;">Finish and forward to Verification</i>
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
                <h4 class="modal-title">Edit Material</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="materials-data">
                    <input type="hidden" name="_token" id="csrfMaterial" value="{{Session::token()}}">

                    <input type="hidden" class="form-control" name="ServiceConnectionId" id="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                    <input type="hidden" id="id" value="">

                    <div class="form-group">
                        <label>Material Description</label>
                        <input id="material-description" type="text" class="form-control" readonly value="">
                    </div>

                    <div class="form-group">
                        <label for="material-cost">Material Cost</label>
                        <input type="number" name="material-cost" id="material-cost" value="" step="any" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="project-requiements">Project Requirements</label>
                        <input type="number" name="Project Requirements" step="any" id="project-requiements" value="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="labor-percentage">Labor Cost Percentage</label>
                        <input type="number" step="any" id="labor-percentage" value="" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="submit">Save changes</button> --}}
                <input type="submit" value="Update" id="submit" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR ADDING MATERIALS --}}
<div class="modal fade" id="modal-add" aria-hidden="true" style="display: none;">
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
                                <option amount="{{ $item->Amount }}" value="{{ $item->id }}">{{ $item->Description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" name="Quantity" id="Quantity" value="1" step="any" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="labor-percentage">Labor Cost Percentage</label>
                        <input type="number" step="any" id="labor-percentage-new" value="0.035" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="submit">Save changes</button> --}}
                <input type="submit" value="AddMaterial" id="addmaterial" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script type="text/javascript">
        function deleteMaterial(id) {
            if (confirm('Are you sure you want to delete this material?')) {
                $.ajax({
                    url : '/preDefinedMaterialsMatrices/' + id,
                    type : 'DELETE',
                    data : {
                        _token:'{{ csrf_token() }}',
                        id : id,
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(response) {
                        alert(response);
                    }
                })
            }            
        }

        $(document).ready(function() {
            $('body').on('click', '#edit', function() {
                var matCost = parseFloat($('#materialcost-' + $(this).attr('matid')).text().replace(',', ''));
                var qty = parseFloat($('#quantity-' + $(this).attr('matid')).text());

                $('#material-description').val($('#description-' + $(this).attr('matid')).text());
                $('#material-cost').val(matCost);
                $('#project-requiements').val(qty);   
                $('#id').val($(this).attr('matid'));
                $('#labor-percentage').val($(this).attr('labor-percentage'));
            });

            $('body').on('click', '#submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url : '/preDefinedMaterialsMatrices/update-data',
                    type : 'POST',
                    data : {
                        _token:'{{ csrf_token() }}',
                        id : $('#id').val(),
                        Amount : $('#material-cost').val(),
                        Quantity : $('#project-requiements').val(),
                        ApplicationType : '{{ $serviceConnection->AccountApplicationType }}',
                        MonthsDuration : '{{ $serviceConnection->TemporaryDurationInMonths }}',
                        LaborPercentage : $('#labor-percentage').val(),
                        Options : "{{ $serviceConnection->Options }}"
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        alert(error);
                    }
                });
            });

            $('body').on('click', '#addmaterial', function(e) {
                var scId = "{{ $serviceConnection->id }}";
                var neaCode = $("#MaterialsId").val();
                var description = $("#MaterialsId option:selected").text()
                var quantity = $('#Quantity').val();
                var options = "{{ $serviceConnection->Options }}"
                var applicationType = "{{ $serviceConnection->AccountApplicationType }}"
                var amount = $("#MaterialsId option:selected").attr('amount')
                var laborPercentage = $('#labor-percentage-new').val()
                var duration = "{{ $serviceConnection->TemporaryDurationInMonths==null ? '0' : $serviceConnection->TemporaryDurationInMonths }}"

                $.ajax({
                    url : '/preDefinedMaterialsMatrices/add-material/',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        ServiceConnectionId : scId,
                        NEACode : neaCode,
                        Description : description,
                        Quantity : quantity,
                        Options : options,
                        ApplicationType : applicationType,
                        Amount : amount,
                        LaborPercentage : laborPercentage,
                        MonthsDuration : duration
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        alert('Error saving data');
                        console.log(error)
                    }
                });
            });
        });
    </script>
@endpush