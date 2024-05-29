{{-- HEADER --}}
@if ($whHeadMeters != null)
<table class="table table-borderless table-sm table-hover">
    <tr>
        <td>Order No :</td>
        <td>
            <input type="text" id="OrderNo" class="form-control form-control-xs text-right" value="{{ $whHeadMeters->orderno }}" readonly>
        </td>
        <td></td>
        <td></td>
        <td>Entry No : </td>
        <td>
            <input type="number" id="EntryNo" class="form-control form-control-xs text-right" value="{{ $whHeadMeters->ent_no }}" readonly>
        </td>
    </tr>
    <tr>
        <td>Date :</td>
        <td>
            <input type="text" id="MIRSDate" class="form-control form-control-xs" value="{{ $whHeadMeters->tdate }}" readonly>
        </td>
        <td></td>
        <td>Invoice No : </td>
        <td>
            <input type="text" id="InvoiceNo" class="form-control form-control-xs" value="{{ $whHeadMeters->invoice }}">
        </td>
        <td rowspan="2">
            <textarea name="MIRSNo" id="MIRSNo" cols="30" rows="2" class="form-control form-control-xs" placeholder="MIRS No:">{{ $whHeadMeters->misno }}</textarea>
        </td>
    </tr>
    <tr>
        <td>Requisition By :</td>
        <td>
            <input type="text" id="RequisitionById" class="form-control form-control-xs text-right" value="{{ $whHeadMeters->emp_id }}" readonly>
        </td>
        <td>
            <input type="text" id="RequisitionByName" class="form-control form-control-xs" value="{{ strtoupper($whHeadMeters->chkby) }}" readonly>
        </td>
        <td>OR No : </td>
        <td>
            <input type="text" id="ORNo" value="{{ $paymentOrder->ORNumber }}" class="form-control form-control-xs">
        </td>
    </tr>
    <tr>
        <td>Cost Center :</td>
        <td>
            <input type="text" id="CostCenter" value="{{ $whHeadMeters->ccode }}"  class="form-control form-control-xs">
        </td>
        <td>
            <input type="text" id="CostCenterDescription" class="form-control form-control-xs" readonly>
        </td>
        <td>Customer: </td>
        <td>
            <input type="text" id="CustomerId" class="form-control form-control-xs" value="{{ $serviceConnections->id }}" readonly>
        </td>
        <td>
            <input type="text" id="CustomerName" class="form-control form-control-xs" value="{{ $serviceConnections->ServiceAccountName }}" readonly>
        </td>
    </tr>
    <tr>
        <td>Charge To :</td>
        <td>
            <input type="text" id="ChargeTo" value="{{ $whHeadMeters->dept }}" class="form-control form-control-xs">
        </td>
        <td></td>
        <td>Type Of Service: </td>
        <td>
            <input type="text" id="TypeOfService" class="form-control form-control-xs" value="{{ $serviceConnections->AccountApplicationType }}" readonly>
        </td>
        <td>
            <input type="text" id="TypeOfServiceId" class="form-control form-control-xs" value="{{ $whHeadMeters->serv_code }}" readonly>
        </td>
    </tr>
    <tr>
        <td>Project Code :</td>
        <td colspan="2">
            <input type="text" id="ProjectCode" value="{{ $whHeadMeters->pcode }}" class="form-control form-control-xs">
        </td>
        <td>Remarks: </td>
        <td colspan="2" rowspan="2">
            <textarea name="Remarks" id="Remarks" cols="30" rows="2" class="form-control form-control-xs" placeholder="Remarks/Notes/Comments"></textarea>
        </td>
    </tr>
    <tr>
        <td>Requested By :</td>
        <td colspan="2">
            <input type="text" id="RequestedBy" class="form-control form-control-xs" value="{{ $whHeadMeters->chkby }}" readonly>
        </td> 
    </tr>
</table>

{{-- ITEMS LIST --}}
<table id="items-list" class="table table-bordered table-sm table-hover">
    <thead>
        <th>Item Code</th>
        <th>Description</th>
        <th>Asset Code</th>
        <th>Quantity</th>
        <th>UOM</th>
        <th>Unit Price</th>
        <th>Sales Price</th>
        <th>Total Cost</th>
    </thead>
    <tbody>
        @php
            $cst = 0;
            $amt = 0;
            $salesprice = 0;
        @endphp
        @foreach ($whItemsMeters as $item)
            <tr>
                <td>{{ $item->itemcd }}</td>
                <td>{{ $item->itm_desc }}</td>
                <td>{{ $item->ascode }}</td>
                <td class="text-right">{{ $item->qty }}</td>
                <td class="text-right">{{ $item->uom }}</td>
                <td class="text-right">{{ is_numeric($item->cst) ? number_format($item->cst, 6, ".", "") : $item->cst }}</td>
                <td class="text-right">{{ is_numeric($item->salesprice) ? number_format($item->salesprice, 6, ".", "") : $item->salesprice }}</td>
                <td class="text-right">{{ is_numeric($item->amt) ? number_format($item->amt, 6, ".", "") : $item->amt }}</td>
            </tr>
            @php
                $cst += (is_numeric($item->cst) ? floatval($item->cst) : 0);
                    $salesprice += (is_numeric($item->salesprice) ? floatval($item->salesprice) : 0);
                $amt += (is_numeric($item->amt) ? floatval($item->amt) : 0);
            @endphp
        @endforeach
        <tr>
            <td colspan="5"><strong>TOTAL</strong></td>
            <td class="text-right"><strong>{{ number_format($cst, 6, ".", "") }}</strong></td>
            <td class="text-right"><strong>{{ number_format($salesprice, 6, ".", "") }}</strong></td>
            <td class="text-right"><strong>{{ number_format($amt, 6, ".", "") }}</strong></td>
        </tr>
    </tbody>
</table>
@else
    <p class="text-center text-info"><i class="fas fa-info-circle ico-tab"></i> No meter invoice recorded!</p>
@endif
