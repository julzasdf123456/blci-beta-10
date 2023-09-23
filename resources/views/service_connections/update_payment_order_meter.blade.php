{{-- HEADER --}}
<table class="table table-borderless table-sm table-hover">
   <tr>
      <td>Order No :</td>
      <td>
         <input type="text" id="meter-OrderNo" class="form-control form-control-xs text-right" value="{{ $whHeadMeters->orderno }}" readonly>
      </td>
      <td></td>
      <td></td>
      <td>Entry No : </td>
      <td>
         <input type="number" id="meter-EntryNo" class="form-control form-control-xs text-right" value="{{ $whHeadMeters != null ? $whHeadMeters->ent_no : 0 }}" readonly>
      </td>
   </tr>
   <tr>
      <td>Date :</td>
      <td>
         <input type="text" id="meter-MIRSDate" class="form-control form-control-xs" value="{{ $whHeadMeters->tdate }}" readonly>
      </td>
      <td></td>
      <td>Invoice No : </td>
      <td>
         <input type="text" id="meter-InvoiceNo" class="form-control form-control-xs" value="{{ $whHeadMeters->invoice }}">
      </td>
      <td rowspan="2">
         <textarea name="MIRSNo" id="meter-MIRSNo" cols="30" rows="2" class="form-control form-control-xs" placeholder="MIRS No:">{{ $whHeadMeters->misno }}</textarea>
      </td>
   </tr>
   <tr>
      <td>Requisition By :</td>
      <td>
         <input type="text" id="meter-RequisitionById" class="form-control form-control-xs text-right" value="{{ $whHeadMeters->emp_id }}" readonly>
      </td>
      <td>
         <input type="text" id="meter-RequisitionByName" class="form-control form-control-xs" value="{{ strtoupper($whHeadMeters->chkby) }}" readonly>
      </td>
      <td>OR No : </td>
      <td>
         <input type="text" id="meter-ORNo" class="form-control form-control-xs" value="{{ $paymentOrder->ORNumber }}">
      </td>
   </tr>
   <tr>
      <td>Cost Center :</td>
      <td>
         {{-- <input type="text" id="meter-CostCenter" class="form-control form-control-xs"> --}}
         <select class="custom-select select2 form-control-xs" name="CostCenter" id="meter-CostCenter">
            <option value="">-- Select --</option>
            @foreach ($costCenters as $item)
               <option value="{{ $item->CostCode }}" data_desc="{{ $item->CostName }}" data_dept="{{ $item->CostDepartment }}" title="{{ $item->CostName }}" {{ $whHeadMeters != null && $whHeadMeters->ccode==$item->CostCode ? 'selected' : '' }}>{{ $item->CostCode }}</option>
            @endforeach
      </select>
      </td>
      <td>
         <input type="text" id="meter-CostCenterDescription" class="form-control form-control-xs" readonly>
      </td>
      <td>Customer: </td>
      <td>
         <input type="text" id="meter-CustomerId" class="form-control form-control-xs" value="{{ $serviceConnection->id }}" readonly>
      </td>
      <td>
         <input type="text" id="meter-CustomerName" class="form-control form-control-xs" value="{{ $serviceConnection->ServiceAccountName }}" readonly>
      </td>
   </tr>
   <tr>
      <td>Charge To :</td>
      <td>
         <input type="text" id="meter-ChargeTo" class="form-control form-control-xs" readonly value="{{ $whHeadMeters->dept }}">
      </td>
      <td></td>
      <td>Type Of Service: </td>
      <td>
         <input type="text" id="meter-TypeOfService" class="form-control form-control-xs" value="NEW INSTALLATION" readonly>
      </td>
      <td>
         <input type="text" id="meter-TypeOfServiceId" class="form-control form-control-xs" value="{{ $whHeadMeters->serv_code }}" readonly>
      </td>
   </tr>
   <tr>
      <td>Project Code :</td>
      <td colspan="2">
         {{-- <input type="text" id="meter-ProjectCode" class="form-control form-control-xs"> --}}
         <select class="custom-select select2 form-control-xs" name="ProjectCode" id="meter-ProjectCode">
            <option value="">-- Select --</option>
            @foreach ($projectCodes as $item)
               <option value="{{ $item->ProjectCode }}" {{ $whHeadMeters != null && $whHeadMeters->pcode==$item->ProjectCode ? 'selected' : '' }}>{{ $item->ProjectCode }} ({{ $item->ProjectDescription }})</option>
            @endforeach
      </select>
      </td>
      <td>Remarks: </td>
      <td colspan="2" rowspan="2">
         <textarea name="Remarks" id="meter-Remarks" cols="30" rows="2" class="form-control form-control-xs" placeholder="Remarks/Notes/Comments"></textarea>
      </td>
   </tr>
   <tr>
      <td>Requested By :</td>
      <td colspan="2">
         <input type="text" id="meter-RequestedBy" class="form-control form-control-xs" value="{{ $whHeadMeters->chkby }}" readonly>
      </td> 
   </tr>
</table>

<div class="divider"></div>

{{-- ITEMS SELECTION --}}
<table class="table table-borderless table-sm table-hover">
   <tr>
      <td>Item Code : </td>
      <td>
         <input type="text" id="meter-ItemCode" class="form-control form-control-xs" value="" readonly>
      </td>
      <td>
         <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#modal-add-meter"><i class="fas fa-plus-circle ico-tab-mini"></i>Add Items</button>
      </td>
      <td>Quantity : </td>
      <td>
         <input type="number" step="any" id="meter-ItemQuantity" class="form-control form-control-xs text-right" value="">
      </td>
      <td rowspan="2" class="text-right">
         <button onclick="addToListMeter()" class="btn btn-lg btn-danger"><i class="fas fa-check-circle ico-tab"></i>Add to List</button>
      </td>
   </tr>
   <tr>
      <td>Description : </td>
      <td colspan="2">
         <input type="text" id="meter-ItemDescription" class="form-control form-control-xs" value="" readonly>
      </td>
      <td>Total Cost : </td>
      <td>
         <input type="number" id="meter-ItemTotalCost" class="form-control form-control-xs text-right" value="" readonly>
      </td>
   </tr>
</table>

{{-- ITEMS LIST --}}
<table id="meter-items-list" class="table table-bordered table-sm table-hover">
   <thead>
      <th>Item Code</th>
      <th>Description</th>
      <th>Asset Code</th>
      <th>Quantity</th>
      <th>UOM</th>
      <th>Unit Price</th>
      <th>Total Cost</th>
   </thead>
   <tbody>
      @php
           $cst = 0;
           $amt = 0;
       @endphp
       @foreach ($whItemsMeters as $item)
           <tr id="{{ $item->id }}">
               <td>{{ $item->itemcd }}</td>
               <td>{{ $item->itm_desc }}</td>
               <td>{{ $item->ascode }}</td>
               <td class="text-right">{{ $item->qty }}</td>
               <td class="text-right">{{ $item->uom }}</td>
               <td class="text-right">{{ is_numeric($item->cst) ? number_format($item->cst, 2) : $item->cst }}</td>
               <td class="text-right">{{ is_numeric($item->amt) ? number_format($item->amt, 2) : $item->amt }}</td>
               <td>
                  <button onclick='removeItem(`{{ $item->id }}`, `OLD`)' class='btn btn-xs btn-link text-danger' style='margin-left: 10px;'><i class='fas fa-trash'></i></button>
               </td>
           </tr>
           @php
               $cst += (is_numeric($item->cst) ? floatval($item->cst) : 0);
               $amt += (is_numeric($item->amt) ? floatval($item->amt) : 0);
           @endphp
       @endforeach
   </tbody>
</table>
@include('service_connections.modal_payment_order_meter')

@push('page_scripts')
   <script>
      $(document).ready(function() {
         $('#meter-CostCenter').change(function() {
            $('#meter-CostCenterDescription').val($('option:selected', this).attr('data_desc'))
            $('#meter-ChargeTo').val($('option:selected', this).attr('data_dept'))
         })
      })   
   </script>    
@endpush