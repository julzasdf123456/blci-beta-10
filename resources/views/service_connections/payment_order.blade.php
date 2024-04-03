@php
    use App\Models\ServiceConnections;
    use App\Models\Barangays;

    $typeOfCustomers = ServiceConnections::typesOfConsumer();
    $brgys = Barangays::orderBy('BarangayCode')->get();
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-6">
               <h4 style="display: inline; margin-right: 15px;">Create Payment Order</h4>
           </div>

           <div class="col-sm-6">
               <button class="btn btn-primary float-right" onclick="savePaymentOrder()"><i class="fas fa-dollar-sign ico-tab"></i>Save Payment Order</button>
               <div id="loader" class="spinner-border text-danger float-right gone" style="margin-right: 10px;" role="status">
                   <span class="sr-only">Loading...</span>
               </div>
           </div>
       </div>
   </div>
</section>

<div class="row">

   <div class="col-lg-9">
      {{-- MATERIAL INQUISITION REQUEST --}}
      <div class="card shadow-none">
         <div class="card-header p-2">
            <ul class="nav nav-pills">
               <li class="nav-item"><a class="nav-link custom-tab active" href="#materials" data-toggle="tab">
                   <i class="fas fa-info-circle"></i>
                   Materials</a></li>
               <li class="nav-item"><a class="nav-link custom-tab" href="#meter" data-toggle="tab">
                   <i class="fas fa-tachometer-alt"></i>
                   Meter</a></li>
            <ul>
         </div>
         <div class="card-body p-0">
            <div class="tab-content">
               {{-- MATERIALS --}}
               <div class="tab-pane active" id="materials">
                  <div class="table-responsive">
                     @include('service_connections.payment_order_materials')
                  </div>
               </div>

               {{-- METER --}}
               <div class="tab-pane" id="meter">
                  <div class="table-responsive">
                     @include('service_connections.payment_order_meter')
                  </div>
               </div>
            </div>            
         </div>
      </div>

      {{-- MATERIAL PRESETS --}}
      @if ($materialPresets != null)
         <div class="card shadow-none">
            <div class="card-header">
               <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Suggested Material Presets From Inspector</span>
               <div class="card-tools">
                  <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
               </div>
            </div>
            <div class="card-body table-responsive p-0">
               <table class="table table-hover table-sm table-bordered">
                  <thead>
                     <th>Materials</th>
                     <th>Unit</th>
                     <th class="text-right">Quantity</th>
                  </thead>
                  <tbody>
                     <tr>
                        <td>METERBASE SOCKET</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->MeterBaseSocket != null ? number_format($materialPresets->MeterBaseSocket, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>METALBOX TYPE A</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->MetalboxTypeA != null ? number_format($materialPresets->MetalboxTypeA, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>METALBOX TYPE B</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->MetalboxTypeB != null ? number_format($materialPresets->MetalboxTypeB, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>PIPE #1</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->Pipe != null ? number_format($materialPresets->Pipe, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>ENTRANCE CAP #1</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->EntranceCap != null ? number_format($materialPresets->EntranceCap, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>ADAPTER #1</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->Adapter != null ? number_format($materialPresets->Adapter, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>LOCKNOT #1</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->Locknot != null ? number_format($materialPresets->Locknot, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>MAILBOX</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->Mailbox != null ? number_format($materialPresets->Mailbox, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>BUCKLE</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->Buckle != null ? number_format($materialPresets->Buckle, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>PVC ELBOW #1</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->PvcElbow != null ? number_format($materialPresets->PvcElbow, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>STAINLESS STRAP</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->StainlessStrap != null ? number_format($materialPresets->StainlessStrap, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>PLYBOARD 17x20 CM</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->Plyboard != null ? number_format($materialPresets->Plyboard, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>STRAIN INSULATOR (M)</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->StrainInsulator != null ? number_format($materialPresets->StrainInsulator, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>STRANDED WIRE #8</td>
                        <td>meters</td>
                        <td class="text-right">{{ $materialPresets->StraindedWireEight != null ? number_format($materialPresets->StraindedWireEight, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>STRANDED WIRE #6</td>
                        <td>meters</td>
                        <td class="text-right">{{ $materialPresets->StrandedWireSix != null ? number_format($materialPresets->StrandedWireSix, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>TWISTED WIRE #6</td>
                        <td>meters</td>
                        <td class="text-right">{{ $materialPresets->TwistedWireSix != null ? number_format($materialPresets->TwistedWireSix, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>TWISTED WIRE #4</td>
                        <td>meters</td>
                        <td class="text-right">{{ $materialPresets->TwistedWireFour != null ? number_format($materialPresets->TwistedWireFour, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>COMPRESSION TAP 2A SU</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->CompressionTapAsu != null ? number_format($materialPresets->CompressionTapAsu, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>COMPRESSION TAP YTD 250</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->CompressionTapYtdTwoFifty != null ? number_format($materialPresets->CompressionTapYtdTwoFifty, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>COMPRESSION TAP YTD 300</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->CompressionTapYtdThreeHundred != null ? number_format($materialPresets->CompressionTapYtdThreeHundred, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>COMPRESSION TAP YTD 200</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->CompressionTapYtdThreeHundred != null ? number_format($materialPresets->CompressionTapYtdThreeHundred, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>COMPRESSION TAP YTD 150</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->CompressionTapYtdOneFifty != null ? number_format($materialPresets->CompressionTapYtdOneFifty, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>METAL SCREW</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->MetalScrew != null ? number_format($materialPresets->MetalScrew, 2) : '-' }}</td>
                     </tr>
                     <tr>
                        <td>ELECTRICAL TAPE</td>
                        <td>pcs</td>
                        <td class="text-right">{{ $materialPresets->ElectricalTape != null ? number_format($materialPresets->ElectricalTape, 2) : '-' }}</td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="card-footer">

            </div>
         </div>
      @endif
   </div>

   <div class="col-lg-3">
      {{-- ACCOUNT NUMBER --}}
      <div class="card shadow-none">
         <div class="card-body">
            <span class="text-muted">ACCOUNT NUMBER</span>
            <br>
            {{-- <input title="Barangay" maxlength="2" type="text" name="BarangayCode" id="BarangayCode" class="form-control" required value="{{ $serviceConnection->BarangayCode }}" style="width: 46px; display: inline;"> --}}
            <select title="Barangay" name="BarangayCode" id="BarangayCode" class="form-control" required style="width: 66px; display: inline;">
               @foreach ($brgys as $item)
                   <option value="{{ $item->BarangayCode }}" {{ $serviceConnection->BarangayCode != null && $serviceConnection->BarangayCode==$item->BarangayCode ? 'selected' : '' }}>{{ $item->BarangayCode }}  - {{ $item->Barangay}}</option>
               @endforeach
            </select>
            <span>-</span>
            {{-- <input title="Type of Customer" maxlength="2" type="text" name="TypeOfCustomer" id="TypeOfCustomer" class="form-control" required value="{{ $serviceConnection->TypeOfCustomer }}" style="width: 46px; display: inline;"> --}}
            <select title="Type of Customer" name="TypeOfCustomer" id="TypeOfCustomer" class="form-control" required style="width: 66px; display: inline;">
               @foreach ($typeOfCustomers as $key => $item)
                   <option value="{{ $key }}" {{ $serviceConnection->TypeOfCustomer != null && $serviceConnection->TypeOfCustomer==$key ? 'selected' : '' }}>{{ $key }}  - {{ $item }}</option>
               @endforeach
            </select>
            <span>-</span>
            <input type="text" name="AccountNumber" maxlength="5" id="AccountNumber" value="{{ $serviceConnection->AccountNumber }}" placeholder="Input Account Number Here" class="form-control" required style="width: 100px; display: inline;">
            <span>-</span>
            <input title="Number of Accounts" maxlength="2" type="text" name="NumberOfAccounts" value="{{ $serviceConnection->NumberOfAccounts }}" id="NumberOfAccounts" class="form-control" required style="width: 46px; display: inline;" value="00">
         </div>
      </div>
      {{-- FEES --}}
     <div class="card shadow-none">
        <div class="card-header">
           <span class="card-title">Fees</span>
        </div>
        <div class="card-body table-responsive p-0">
           <table class="table table-sm table-borderless table-hover">
              <tr>
                 <td>Material Deposit</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="MaterialDeposit" name="MaterialDeposit" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Over-head Expenses</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="OverheadExpenses" name="OverheadExpenses" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Transformer Rental Fees</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="TransformerRentalFees" name="TransformerRentalFees" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Apprehension</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="Apprehension" name="Apprehension" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Customer Deposit</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="CustomerDeposit" name="CustomerDeposit" class="form-control form-control-sm text-right">
                 </td>
              </tr>
              <tr>
                 <td>CIAC</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="CIAC" name="CIAC" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Service Fee</td>
                 <td>
                    <input type="number" step="any" onkeyup="validateTotalInputs()" id="ServiceFee" name="ServiceFee" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
           </table>
        </div>
     </div>

     {{-- SUMMARY --}}
     <div class="card shadow-none">
        <div class="card-header">
           <span class="card-title">Summary</span>
        </div>
        <div class="card-body table-responsive">
           <table class="table table-hover table-borderless table-sm">
               <tbody>
                  <tr>
                     <td>Sale of Materials</td>
                     <td>
                        <input type="number" step="any" value="" onchange="validateTotalInputs()" onkeyup="validateTotalInputs()" id="SaleOfMaterials" name="SaleOfMaterials" class="form-control form-control-sm text-right" autofocus>
                     </td>
                  </tr>
                  <tr>
                     <td>Others</td>
                     <td>
                        <input type="number" step="any" onchange="validateTotalInputs()" onkeyup="validateTotalInputs()" id="Others" name="Others" class="form-control form-control-sm text-right" autofocus>
                     </td>
                  </tr>
                  <tr>
                     <td>Materials Total</td>
                     <td>
                        <input type="number" step="any" onchange="validateTotalInputs()" onkeyup="validateTotalInputs()" id="MaterialsTotal" name="MaterialsTotal" class="form-control form-control-sm text-right" autofocus>
                     </td>
                  </tr>
               </tbody>
           </table>

           <div class="divider"></div>

           <table class="table table-hover table-borderless table-sm">
              <tbody>
                  <tr>
                     <th>Local F. Tax</th>
                     <th>
                        <input type="number" step="any" id="LocalFTax" name="LocalFTax" class="form-control form-control-sm text-right" autofocus>
                     </th>
                  </tr>
                  <tr>
                     <th>Sub-total w/o VAT</th>
                     <th>
                        <input type="number" step="any" id="SubTotal" name="SubTotal" class="form-control form-control-sm text-right" autofocus>
                     </th>
                  </tr>
                  <tr>
                     <th>VAT</th>
                     <th>
                        <input type="number" step="any" id="VAT" name="VAT" class="form-control form-control-sm text-right" autofocus>
                     </th>
                  </tr>
                  <tr>
                     <th>Others</th>
                     <th>
                        <input type="number" step="any" onkeyup="validateTotalInputs()" id="OthersTotal" name="OthersTotal" class="form-control form-control-sm text-right" autofocus>
                     </th>
                  </tr>
                  <tr>
                     <th><h4>TOTAL</h4></th>
                     <th>
                        <input type="number" step="any" style="font-size: 1.3em;" id="OverAllTotal" name="OverAllTotal" class="form-control form-control-sm text-right" autofocus>
                     </th>
                  </tr>
              </tbody>
           </table>
        </div>
     </div>
  </div>

   <div class="col-lg-12" style="margin-bottom: 15px;">
      <button class="btn btn-primary float-right" onclick="savePaymentOrder()"><i class="fas fa-dollar-sign ico-tab"></i>Save Payment Order</button>
      <div id="loader" class="spinner-border text-danger float-right gone" style="margin-right: 10px;" role="status">
         <span class="sr-only">Loading...</span>
     </div>
  </div>
</div>

@endsection

@push('page_scripts')
    <script>
      /**
       * GLOBAL VARIABLES
       * ===================================
       **/
      var selectedItemCost = 0 // initialized in modal_payment_order_items
      var selectedUOM = '' // initialized in modal_payment_order_items
      var selectedUnitPrice = 0 // initialized in modal_payment_order_items

      $(document).ready(function() {
         $('body').addClass('sidebar-collapse')

         $('#modal-add-items').on('hidden.bs.modal', function() {
            setTimeout(function (){
               $('#ItemQuantity').focus();
            }, 90);
         });

         $('#modal-add-meter').on('hidden.bs.modal', function() {
            setTimeout(function (){
               $('#meter-ItemQuantity').focus();
            }, 90);
         });

         // ITEMS
         $('#ItemQuantity').keyup(function() {
            var qty = 0
            if(jQuery.isEmptyObject(this.value)) {
               qty = 0
            } else {
               qty = parseFloat(this.value)
            }

            var total = getTotalItemCost(qty, selectedItemCost)

            $('#ItemTotalCost').val(Math.round((total + Number.EPSILON) * 100) / 100)
         })

         $('#ItemQuantity').change(function() {
            var qty = 0
            if(jQuery.isEmptyObject(this.value)) {
               qty = 0
            } else {
               qty = parseFloat(this.value)
            }

            var total = getTotalItemCost(qty, selectedItemCost)

            $('#ItemTotalCost').val(Math.round((total + Number.EPSILON) * 100) / 100)
         })

         // METER
         $('#meter-ItemQuantity').keyup(function() {
            var qty = 0
            if(jQuery.isEmptyObject(this.value)) {
               qty = 0
            } else {
               qty = parseFloat(this.value)
            }

            var total = getTotalItemCost(qty, selectedItemCost)

            $('#meter-ItemTotalCost').val(Math.round((total + Number.EPSILON) * 100) / 100)
         })

         $('#meter-ItemQuantity').change(function() {
            var qty = 0
            if(jQuery.isEmptyObject(this.value)) {
               qty = 0
            } else {
               qty = parseFloat(this.value)
            }

            var total = getTotalItemCost(qty, selectedItemCost)

            $('#meter-ItemTotalCost').val(Math.round((total + Number.EPSILON) * 100) / 100)
         })

         $('#CostCenter').change(function() {
            $('#CostCenterDescription').val($('option:selected', this).attr('data_desc'))
            $('#ChargeTo').val($('option:selected', this).attr('data_dept'))
         })
         
         $('#ItemQuantity').on('keydown', function(event) {
            if (event.which == 13 || event.keyCode == 13) {
               event.preventDefault();
               addToList()
            }
         });

         $('#meter-ItemQuantity').on('keydown', function(event) {
            if (event.which == 13 || event.keyCode == 13) {
               event.preventDefault();
               addToListMeter()
            }
         });

         /** 
          * FEES AREA
          * =================================
          */
         $('#CustomerDeposit').val("{{ $inspection != null && $inspection->BillDeposit != null ? $inspection->BillDeposit : 0 }}").change()
         $('#CustomerDeposit').trigger('onkeyup')
      })

      /**
       *  MATERIAL ITEMS
       * ======================================
       */
      function getTotalItemCost(qty, cost) {
         var total = qty * cost

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function addToList() {
         if (jQuery.isEmptyObject($('#ItemQuantity').val()) | jQuery.isEmptyObject($('#ItemCode').val()) | jQuery.isEmptyObject($('#ItemDescription').val())) {
            Toast.fire({
               icon : 'warning',
               text : 'please select item first!'
            })
         } else {
            var qty = $('#ItemQuantity').val()
            $('#items-list tbody').append(addRowItem($('#ItemCode').val(), $('#ItemDescription').val(), qty, selectedUOM, selectedItemCost, $('#ItemTotalCost').val(), selectedUnitPrice))
            $('#MaterialsTotal').val(getItemTotal())
            $('#MaterialsTotal').change()
            clearSelection()
         }         
      }

      function addToListMeter() {
         if (jQuery.isEmptyObject($('#meter-ItemQuantity').val()) | jQuery.isEmptyObject($('#meter-ItemCode').val()) | jQuery.isEmptyObject($('#meter-ItemDescription').val())) {
            Toast.fire({
               icon : 'warning',
               text : 'please select meter first!'
            })
         } else {
            var qty = $('#meter-ItemQuantity').val()
            $('#meter-items-list tbody').append(addRowItem($('#meter-ItemCode').val(), $('#meter-ItemDescription').val(), qty, selectedUOM, selectedItemCost, $('#meter-ItemTotalCost').val(), selectedUnitPrice))
            clearSelection()
         }         
      }

      function addRowItem(itmcode, desc, qty, uom, uprice, tcost, selectedUnitPrice) {
         const d = new Date()
         var id = d.getTime()
         return "<tr id='" + id + "'>" +
                  "<td>" + itmcode + "</td>" +
                  "<td>" + desc + "</td>" +
                  "<td></td>" +                  
                  "<td class='text-right'>" + qty + "</td>" +
                  "<td>" + uom + "</td>" +
                  "<td class='text-right'>" + Number(parseFloat(uprice)).toFixed(6) + "</td>" +
                  "<td class='text-right'>" + Number(parseFloat(selectedUnitPrice)).toFixed(6) + "</td>" +
                  "<td class='text-right text-primary'>" + Number(parseFloat(tcost)).toFixed(6) + "</td>" + 
                  "<td class='text-right'>" +
                     "<button onclick='removeItem(`" + id + "`)' class='btn btn-xs btn-link text-danger' style='margin-left: 10px;'><i class='fas fa-trash'></i></button>" +
                  "</td>" +
               "<tr>"
      }

      function getItemTotal() {
         var total = 0
         $('#items-list tr').each(function() {
            var obj = $('td', this).eq(7).text() // get 6th index which is the total cost
            if (!jQuery.isEmptyObject(obj)) {
               try {
                  total += parseFloat(obj)
               } catch (err) {
                  total += 0
               }
            } else {
               total += 0
            }            
         })

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function clearSelection() {
         selectedItemCost = 0
         selectedUOM = ''
         $('#ItemCode').val('')
         $('#ItemDescription').val('')
         $('#ItemQuantity').val('')
         $('#ItemTotalCost').val('')

         $('#meter-ItemCode').val('')
         $('#meter-ItemDescription').val('')
         $('#meter-ItemQuantity').val('')
         $('#meter-ItemTotalCost').val('')
      }

      // MATERIALS
      function getMaterialItemsForPosting() {
         var data = []

         $('#items-list tbody tr').each(function(index, element) {    
            if (!jQuery.isEmptyObject($('td', this).eq(0).text())) {
               data.push({
                  ReqNo : $('#OrderNo').val(),
                  EntryNo : $('#EntryNo').val(),
                  ItemCode : $('td', this).eq(0).text(),
                  ItemQuantity : $('td', this).eq(3).text(),
                  ItemUOM : $('td', this).eq(4).text(),
                  ItemUnitPrice : isNull($('td', this).eq(5).text()) ? 0 : $('td', this).eq(5).text(),
                  ItemSalesPrice : isNull($('td', this).eq(6).text()) ? 0 : $('td', this).eq(6).text(),
                  ItemTotalCost : isNull($('td', this).eq(7).text()) ? 0 : $('td', this).eq(7).text(),
                  ItemNo : (index + 1),
               })
            }            
         })

         return data
      }

      // METERS
      function getMetersForPosting() {
         var data = []

         $('#meter-items-list tbody tr').each(function(index, element) {    
            if (!jQuery.isEmptyObject($('td', this).eq(0).text())) {
               data.push({
                  ReqNo : $('#meter-OrderNo').val(),
                  EntryNo : $('#meter-EntryNo').val(),
                  ItemCode : $('td', this).eq(0).text(),
                  ItemQuantity : $('td', this).eq(3).text(),
                  ItemUOM : $('td', this).eq(4).text(),
                  ItemUnitPrice : $('td', this).eq(5).text(),
                  ItemSalesPrice : $('td', this).eq(6).text(),
                  ItemTotalCost : $('td', this).eq(7).text(),
                  ItemNo : (index + 1),
               })
            }            
         })

         return data
      }

      /**
       * FEES AND SUMMARY
       * ======================================
       */
      function getLocalFTax(amount) {
         if (jQuery.isEmptyObject(amount)) {
            return 0
         } else {
            amount = parseFloat(amount)
            return amount * .0075
         }
      }

      function getInputAmount(amount) {
         if (jQuery.isEmptyObject(amount)) {
            return 0
         } else {
            amount = parseFloat(amount)
            return amount
         }
      }

      function getTotalFTax() {
         var overheadExpenses = getLocalFTax($('#OverheadExpenses').val())
         var transformerRentalFees = getLocalFTax($('#TransformerRentalFees').val())
         var apprehension = getLocalFTax($('#Apprehension').val())
         var serviceFee = getLocalFTax($('#ServiceFee').val())
         var materialsTotal = getLocalFTax($('#MaterialsTotal').val())
         var others = getLocalFTax($('#Others').val())
         var saleOfMaterials = getLocalFTax($('#SaleOfMaterials').val())

         var total = overheadExpenses + transformerRentalFees + apprehension + serviceFee + materialsTotal + others + saleOfMaterials

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function getSubTotal() {
         var materialDeposit = getInputAmount($('#MaterialDeposit').val())
         var overheadExpenses = getInputAmount($('#OverheadExpenses').val())
         var transformerRentalFees = getInputAmount($('#TransformerRentalFees').val())
         var apprehension = getInputAmount($('#Apprehension').val())
         var customerDeposit = getInputAmount($('#CustomerDeposit').val())
         var ciac = getInputAmount($('#CIAC').val())
         var serviceFee = getInputAmount($('#ServiceFee').val())
         var others = getInputAmount($('#Others').val())
         var othersTotal = getInputAmount($('#OthersTotal').val())
         var saleOfMaterials = getLocalFTax($('#SaleOfMaterials').val())
         var materialsTotal = getInputAmount($('#MaterialsTotal').val())

         var total = materialDeposit + overheadExpenses + transformerRentalFees + apprehension + customerDeposit + others + ciac + serviceFee + othersTotal + materialsTotal + saleOfMaterials

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function getTotalVat() {
         var overheadExpenses = (getInputAmount($('#OverheadExpenses').val()) + getLocalFTax($('#OverheadExpenses').val())) * .12
         var transformerRentalFees = (getInputAmount($('#TransformerRentalFees').val()) + getLocalFTax($('#TransformerRentalFees').val())) * .12
         var apprehension = (getInputAmount($('#Apprehension').val()) + getLocalFTax($('#Apprehension').val())) * .12
         var serviceFee = (getInputAmount($('#ServiceFee').val()) + getLocalFTax($('#ServiceFee').val())) * .12
         var materialsTotal = (getInputAmount($('#MaterialsTotal').val()) + getLocalFTax($('#MaterialsTotal').val())) * .12
         var others = (getInputAmount($('#Others').val()) + getLocalFTax($('#Others').val())) * .12
         var saleOfMaterials = (getInputAmount($('#SaleOfMaterials').val()) + getLocalFTax($('#SaleOfMaterials').val())) * .12

         var total = overheadExpenses + transformerRentalFees + apprehension + serviceFee + materialsTotal + others + saleOfMaterials 

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function getOverAllTotal() {
         var fTax = getInputAmount($('#LocalFTax').val())
         var subTotal = getInputAmount($('#SubTotal').val())
         var vat = getInputAmount($('#VAT').val())

         return Math.round(((fTax + subTotal + vat) + Number.EPSILON) * 100) / 100
      }

      function validateTotalInputs() {
         $('#LocalFTax').val(getTotalFTax())
         $('#VAT').val(getTotalVat())
         $('#SubTotal').val(getSubTotal())
         $('#OverAllTotal').val(getOverAllTotal())
      }

      /**
       * SAVE ORDER
       * ========================
       */
      function savePaymentOrder() {
         var brgyCode = $('#BarangayCode').val()
         var typeOfCustomer = $('#TypeOfCustomer').val()
         var acctNo = $('#AccountNumber').val()
         var noOfAccounts = $('#NumberOfAccounts').val()
         if (jQuery.isEmptyObject(acctNo) | jQuery.isEmptyObject(brgyCode) | jQuery.isEmptyObject(typeOfCustomer) | jQuery.isEmptyObject(noOfAccounts)) {
            Toast.fire({
               icon : 'warning',
               text : 'Please supply account number'
            })
         } else {
            $('#loader').removeClass('gone')
            $.ajax({
               url : "{{ route('serviceConnections.save-payment-order') }}",
               type : 'POST',
               data : {
                  _token : "{{ csrf_token() }}",
                  MaterialItems : JSON.stringify(getMaterialItemsForPosting()),
                  MeterItems : JSON.stringify(getMetersForPosting()),
                  ServiceConnectionId : "{{ $serviceConnection->id }}",
                  MaterialDeposit : getInputAmount($('#MaterialDeposit').val()),
                  TransformerRentalFees : getInputAmount($('#TransformerRentalFees').val()),
                  Apprehension : getInputAmount($('#Apprehension').val()),
                  OverheadExpenses : getInputAmount($('#OverheadExpenses').val()),
                  CIAC : getInputAmount($('#CIAC').val()),
                  ServiceFee : getInputAmount($('#ServiceFee').val()),
                  CustomerDeposit : getInputAmount($('#CustomerDeposit').val()),
                  Others : getInputAmount($('#Others').val()),
                  SaleOfMaterials : getInputAmount($('#SaleOfMaterials').val()),
                  LocalFTax : getInputAmount($('#LocalFTax').val()),
                  SubTotal : getInputAmount($('#SubTotal').val()),
                  VAT : getInputAmount($('#VAT').val()),
                  OthersTotal : getInputAmount($('#OthersTotal').val()),
                  OverAllTotal : getInputAmount($('#OverAllTotal').val()),
                  ORNumber : getInputAmount($('#ORNo').val()),
                  MaterialTotal : getInputAmount($('#MaterialsTotal').val()),
                  ReqNo : $('#OrderNo').val(),
                  MIRSNo : $('#MIRSNo').val(),
                  CostCenter : $('#CostCenter').val(),
                  ChargeTo : $('#ChargeTo').val(),
                  ProjectCode : $('#ProjectCode').val(),
                  RequisitionById : $('#RequisitionById').val(),
                  RequestedBy : $('#RequestedBy').val(),
                  InvoiceNo : $('#InvoiceNo').val(),
                  CustomerName : $('#CustomerName').val(),
                  TypeOfServiceId : $('#TypeOfServiceId').val(),
                  EntryNo : $('#EntryNo').val(),
                  MeterReqNo : $('#meter-OrderNo').val(),
                  MeterMIRSNo : $('#meter-MIRSNo').val(),
                  MeterCostCenter : $('#meter-CostCenter').val(),
                  MeterChargeTo : $('#meter-ChargeTo').val(),
                  MeterProjectCode : $('#meter-ProjectCode').val(),
                  MeterRequestedById : $('#meter-RequisitionById').val(),
                  MeterRequestedBy : $('#meter-RequestedBy').val(),
                  MeterInvoiceNo : $('#meter-InvoiceNo').val(),
                  MeterCustomerName : $('#meter-CustomerName').val(),
                  MeterTypeOfServiceId : $('#meter-TypeOfServiceId').val(),
                  MeterEntryNo : $('#meter-EntryNo').val(),
                  AccountNumber : acctNo,
                  TypeOfCustomer : typeOfCustomer,
                  BarangayCode : brgyCode,
                  NumberOfAccounts : noOfAccounts,
                  IsNew : 'New',
               },
               success : function(res) {
                  Toast.fire({
                     icon : 'success',
                     text : 'payment saved!'
                  })
                  $('#loader').removeClass('gone')
                  window.location.href = "{{ url('/serviceConnections') }}/{{ $serviceConnection->id }}"
               },
               error : function(err) {
                  Toast.fire({
                     icon : 'error',
                     text : 'error saving payment order'
                  })
                  console.log(err)
                  $('#loader').removeClass('gone')
               }
            })
         }
         
      }

      function removeItem(id) {
         Swal.fire({
            title: 'Do you want to delete this item?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: `No`,
         }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               $('#' + id).remove()
               $('#MaterialsTotal').val(getItemTotal())
               $('#MaterialsTotal').change()
               validateTotalInputs()
               clearSelection()
               Toast.fire({
                  icon : 'success',
                  text : 'item removed!'
               })
            }
         })
         
      }
    </script>
@endpush