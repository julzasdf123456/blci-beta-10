<?php

use App\Models\IDGenerator;
use App\Models\ServiceConnections;

$id = IDGenerator::generateID();

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Service Connection Invoice</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="content px-3">

                @include('adminlte-templates::common.errors')

                {{-- <div class="callout callout-info">
                    Step <strong>4</strong> of 4 - <strong>Service Connection Payments</strong>
                </div> --}}

                <div class="invoice p-3 mb-3">
                    {{-- <div class="row">
                        <div class="col-12">
                            <h4>
                                <i class="fas fa-globe"></i>{{ env('APP_COMPANY') }}
                                <small class="float-right">Date: {{ date('F d, Y') }}</small>
                            </h4>
                        </div>
                        <!-- /.col -->
                    </div> --}}

                    <div class="row">
                        {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                            <p>Material Payments</p>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Materials ({{ $serviceConnection->BuildingType }})</label>
                                        <select id="materials" class="form-control form-control-sm">
                                           @foreach ($materials as $material)
                                               <option rate="{{ $material->Rate }}" vat="{{ $material->VatPercentage }}" value="{{ $material->id }}">{{ $material->Material }}</option>
                                           @endforeach
                                        </select>
                                    </div>                                    
                                </div>

                                <input type="hidden" name="_token" id="csrfMaterials" value="{{Session::token()}}">

                                <div class="col-lg-4">
                                    <div class="form-group-sm">
                                        <label>Quantity</label>
                                        <input id="material_qty" class="form-control form-control-sm" type="number" step="any" placeholder="Quantity of Materials">
                                    </div>                                    
                                </div>

                                <div class="col-lg-4">
                                    <label style="opacity: 0; display: block;">Action</label>
                                    <button id="add_materials" class="btn btn-sm btn-primary">Add</button>                                   
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <table id="materials_table" class="table">
                                        <thead>
                                            <th>Materials</th>
                                            <th>Rate</th>
                                            <th>Qty</th>
                                            <th>Sub Ttl</th>
                                            <th>VAT</th>
                                            <th>Total</th>
                                            <th width=10></th>
                                        </thead>
                                        <tbody>
                                            @if ($materialPayments != null)
                                                @foreach ($materialPayments as $item)
                                                    <tr id="{{ $item->id }}">
                                                        <td>{{ $item->Material }}</td>
                                                        <td>{{ $item->Rate }}</td>
                                                        <td>{{ $item->Quantity }}</td>
                                                        <td class="text-right">{{ number_format($item->Rate * $item->Quantity, 2) }}</td>
                                                        <td class="text-right">{{ $item->Vat }}</td>  
                                                        <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                                                        <td>
                                                            <button class='btn btn-xs btn-danger' onClick='deleteMaterials({{ $item->id }})'><i class='fas fa-trash'></i></button>
                                                        </td>  
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                    <p>Materials Total: <strong id="totalMaterials">0.0</strong></p>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <p>Particulars and Others</p>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <select id="particulars" class="form-control form-control-sm">
                                           @foreach ($particulars as $particular)
                                               <option default-amount="{{ $particular->DefaultAmount }}" vat="{{ $particular->VatPercentage }}" value="{{ $particular->id }}">{{ $particular->Particular }}</option>
                                           @endforeach
                                        </select>
                                    </div>                                    
                                </div>

                                <input type="hidden" name="_token" id="csrfParticulars" value="{{Session::token()}}">

                                <div class="col-lg-4">
                                    <div class="form-group-sm">
                                        <label>Amount</label>
                                        <input id="particular_amt" class="form-control form-control-sm" type="number" step="any" placeholder="Amount">
                                    </div>                                    
                                </div>

                                <div class="col-lg-4">
                                    <label style="opacity: 0; display: block;">Action</label>
                                    <button id="add_particular" class="btn btn-sm btn-primary">Add</button>                                   
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <table id="particulars_table" class="table">
                                        <thead>
                                            <th>Particulars</th>
                                            <th>Amnt</th>
                                            <th>VAT</th>
                                            <th>Total</th>
                                            <th width=10></th>
                                        </thead>
                                        <tbody>
                                            @if ($particularPayments != null)
                                                @foreach ($particularPayments as $item)
                                                    <tr id="{{ $item->id }}">
                                                        <td>{{ $item->Particular }}</td>  
                                                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                                        <td class="text-right">{{ 0 }}</td>  
                                                        <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                                                        <td>
                                                            <button class='btn btn-xs btn-danger' onClick='deleteParticulars("{{ $item->id }}")'><i class='fas fa-trash'></i></button>
                                                        </td>  
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                    <p>Particulars Total: <strong id="totalParticulars">0.0</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <p>Overall Total</p>

                    <div class="row">
                        <div class="col-md-12">

                            @if ($totalPayments == null)
                                {!! Form::open(['route' => 'serviceConnectionTotalPayments.store']) !!}

                                <input type="hidden" name="id" value="{{ $id }}">

                                <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">
                            @else
                            {!! Form::model($totalPayments, ['route' => ['serviceConnectionTotalPayments.update', $totalPayments->id], 'method' => 'patch']) !!}
                            
                                <input type="hidden" name="id" value="{{ $totalPayments->id }}">

                                <input type="hidden" name="ServiceConnectionId" value="{{ $totalPayments->ServiceConnectionId }}">
                            @endif                            

                            <div class="row">

                                @include('service_connection_total_payments.fields')
                            </div>

                            <div class="card-footer">
                                {!! Form::submit('Submit Payment', ['class' => 'btn btn-primary']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            /**
             * MATERIALS
             */
            calculateTableColumn('materials_table', 5, 'totalMaterials');
            calculateTableColumn('particulars_table', 3, 'totalParticulars');

            displaySubTotal();
            displayTotalVat();
            displayOverAllTotal();

            $('#add_materials').on('click', function() {
                var materialId = $('#materials').val();
                var qty = $('#material_qty').val();
                var rate = $('#materials option:selected').attr('rate');
                var material = $('#materials option:selected').text();
                var vat = $('#materials option:selected').attr('vat');

                if (jQuery.isEmptyObject(qty)) {
                    alert('Please provide value for Quantity!');
                } else {
                    var subTotal = parseFloat(rate * qty);
                    var vatValue = /*parseFloat(subTotal * vat);*/ 0
                    var total = subTotal + vatValue;

                    var d = new Date();
                    var matIdValue = d.getTime();
                    var scId = $('#scId').text();

                    $.ajax({
                        url : '/serviceConnectionMatPayments',
                        type: "POST",
                        data: {
                            _token: $("#csrfMaterials").val(),
                            id: matIdValue,
                            ServiceConnectionId: "{{ $serviceConnection->id }}",
                            Material: materialId,
                            Quantity: qty,
                            Vat: /* vatValue.toFixed(2),*/ null,
                            Total: total.toFixed(2),
                        },
                        success : function(data) {
                            $('#materials_table tbody').append(addRowToMaterials(matIdValue, material, rate, qty, subTotal.toFixed(2), vatValue.toFixed(2), total.toFixed(2)));
                            calculateTableColumn('materials_table', 5, 'totalMaterials');
                            displaySubTotal();
                            displayTotalVat();
                            displayOverAllTotal();
                        },
                        error : function(error) {
                            console.log(error);
                            alert("Error inserting material " + error);
                        }
                    });                    
                }
            });

            /** 
             * PARTICULARS
             */
            $('#particulars').on('change', function() {
                $('#particular_amt').val($('#particulars option:selected').attr('default-amount'));
            });

            $('#add_particular').on('click', function() {
                var particularId = $('#particulars').val();
                var amnt = $('#particular_amt').val();
                var particular = $('#particulars option:selected').text();
                var vat = $('#particulars option:selected').attr('vat');

                if (jQuery.isEmptyObject(amnt)) {
                    alert('Please provide value for Amount!');
                } else {
                    var vatValue = /*parseFloat(amnt * vat);*/ 0
                    var d = new Date();
                    var particularPaymentIdValue = d.getTime();
                    var total = parseFloat(amnt) /* + vatValue;*/
                    var scId = $('#scId').text();

                    $.ajax({
                        url : '/serviceConnectionPayTransactions',
                        type: "POST",
                        data: {
                            _token: $("#csrfParticulars").val(),
                            id: particularPaymentIdValue,
                            ServiceConnectionId: "{{ $serviceConnection->id }}",
                            Particular: particularId,
                            Amount: parseFloat(amnt).toFixed(2),
                            Vat: /* vatValue.toFixed(2),*/ null,
                            Total: total.toFixed(2),
                        },
                        success : function(data) {
                            $('#particulars_table tbody').append(addRowToParticulars(particularPaymentIdValue, particular, parseFloat(amnt).toFixed(2), vatValue.toFixed(2), total.toFixed(2)));
                            calculateTableColumn('particulars_table', 3, 'totalParticulars');
                            displaySubTotal();
                            displayTotalVat();
                            displayOverAllTotal();
                        },
                        error : function(error) {
                            console.log(error);
                            alert("Error inserting material " + error);
                        }
                    });    
                }
            });
        });

        function addRowToMaterials(id, material, rate, qty, subTotal, vat, total) {
            return "<tr id='" + id + "'>" +
                        "<td>" + material + "</td>" +
                        "<td>" + rate + "</td>" +
                        "<td>" + qty + "</td>" +
                        "<td class='text-right'>" + subTotal + "</td>" +
                        "<td class='text-right'>" + vat + "</td>" +
                        "<td class='text-right'>" + total + "</td>" +
                        "<td><button class='btn btn-xs btn-danger' onClick=deleteMaterials('" + id + "')><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";
        }
       
        function addRowToParticulars(id, particular, amnt, vat, total) {
            return "<tr id='" + id + "'>" +
                        "<td>" + particular + "</td>" +
                        "<td class='text-right'>" + amnt + "</td>" +
                        "<td class='text-right'>" + vat + "</td>" +
                        "<td class='text-right'>" + total + "</td>" +
                        "<td><button class='btn btn-xs btn-danger' onClick=deleteParticulars('" + id + "')><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";
        }

        function deleteMaterials(id) {
            if (confirm('Are you sure you want to delete this material?')) {
                $.ajax({
                    url : '/serviceConnectionMatPayments/' + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    success : function(data) {
                        $('#' + id).remove();
                        calculateTableColumn('materials_table', 5, 'totalMaterials');
                        displaySubTotal();
                        displayTotalVat();
                        displayOverAllTotal();
                    },
                    error : function(error) {
                        console.log(error);
                        alert("Error inserting material " + error);
                    }
                });  
            } else {

            }            
        }

        function deleteParticulars(id) {
            if (confirm('Are you sure you want to delete this particular?')) {
                $.ajax({
                    url : '/serviceConnectionPayTransactions/' + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    success : function(data) {
                        $('#' + id).remove();
                        calculateTableColumn('particulars_table', 3, 'totalParticulars');
                        displaySubTotal();
                        displayTotalVat();
                        displayOverAllTotal();
                    },
                    error : function(error) {
                        console.log(error);
                        alert("Error inserting material " + error);
                    }
                });  
            } else {

            }            
        }

        function calculateTableColumn(table, index, display) {
            var total = 0;
            $('#' + table + ' tr').each(function() {
                var value = parseFloat($('td', this).eq(index).text().replace(',', ''));
                console.log(value);
                if (!isNaN(value)) {
                    total += parseFloat(value);
                }
            });
            $('#' + display).text(total.toLocaleString('en-US', {maximumFractionDigits:2}));
        }

        function calculateTableColumnRaw(table, index, display) {
            var total = 0;
            $('#' + table + ' tr').each(function() {
                var value = parseFloat($('td', this).eq(index).text().replace(',', ''));
                console.log(value);
                if (!isNaN(value)) {
                    total += parseFloat(value);
                }
            });
            return total.toFixed(2);
        }

        function displaySubTotal() {
            var mat = calculateTableColumnRaw('materials_table', 3, 'totalMaterials');
            var prt = calculateTableColumnRaw('particulars_table', 1, 'totalParticulars');

            var subTtl = parseFloat(mat) + parseFloat(prt);

            $('#SubTotalField').val(subTtl.toFixed(2));
        }

        function displayTotalVat() {
            var mat = calculateTableColumnRaw('materials_table', 4, 'totalMaterials');
            var prt = calculateTableColumnRaw('particulars_table', 2, 'totalParticulars');

            var vat = parseFloat(mat) + parseFloat(prt);

            $('#TotalVatField').val(vat.toFixed(2));
        }

        function displayOverAllTotal() {
            var subTtl = parseFloat($('#SubTotalField').val());
            var vat = parseFloat($('#TotalVatField').val());
            var total = subTtl + vat;
            $('#TotalField').val(total.toFixed(2));
        }
    </script>
@endpush
