@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-6">
               <h4 style="display: inline; margin-right: 15px;">Update Payment Order</h4>
           </div>

           <div class="col-sm-6">
               <button class="btn btn-danger float-right" onclick="savePaymentOrder()"><i class="fas fa-dollar-sign ico-tab"></i>Update Payment Order</button>
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
               <li class="nav-item"><a class="nav-link active" href="#materials" data-toggle="tab">
                   <i class="fas fa-info-circle"></i>
                   Materials</a></li>
               <li class="nav-item"><a class="nav-link" href="#meter" data-toggle="tab">
                   <i class="fas fa-tachometer-alt"></i>
                   Meter</a></li>
            <ul>
         </div>
         <div class="card-body p-3">
            <div class="tab-content">
               {{-- MATERIALS --}}
               <div class="tab-pane active" id="materials">
                  @include('service_connections.update_payment_order_materials')
               </div>

               {{-- METER --}}
               <div class="tab-pane" id="meter">
                  @include('service_connections.update_payment_order_meter')
               </div>
            </div>  
         </div>
      </div>
   </div>

   <div class="col-lg-3">
      {{-- ACCOUNT NUMBER --}}
      <div class="card shadow-none">
         <div class="card-body">
            <span class="text-muted">ACCOUNT NUMBER</span>
            <input type="text" name="AccountNumber" id="AccountNumber" placeholder="Input Account Number Here" class="form-control" required value="{{ $serviceConnection->AccountNumber }}">
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
                    <input type="number" step="any" value="{{ $paymentOrder->MaterialDeposit }}" onkeyup="validateTotalInputs()" id="MaterialDeposit" name="MaterialDeposit" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Over-head Expenses</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->OverheadExpenses }}" onkeyup="validateTotalInputs()" id="OverheadExpenses" name="OverheadExpenses" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Transformer Rental Fees</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->TransformerRentalFees }}" onkeyup="validateTotalInputs()" id="TransformerRentalFees" name="TransformerRentalFees" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Apprehension</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->Apprehension }}" onkeyup="validateTotalInputs()" id="Apprehension" name="Apprehension" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Customer Deposit</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->CustomerDeposit }}" onkeyup="validateTotalInputs()" id="CustomerDeposit" name="CustomerDeposit" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>CIAC</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->CIAC }}" onkeyup="validateTotalInputs()" id="CIAC" name="CIAC" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
                 <td>Service Fee</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->ServiceFee }}" onkeyup="validateTotalInputs()" id="ServiceFee" name="ServiceFee" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
           </table>
        </div>
     </div>

     {{-- SUMMARY --}}
     <div class="card shadow-none card-success">
        <div class="card-header">
           <span class="card-title">Summary</span>
        </div>
        <div class="card-body table-responsive">
           <table class="table table-hover table-borderless table-sm">
              <tr>
                 <td>Others</td>
                 <td>
                    <input type="number" step="any" value="{{ $paymentOrder->Others }}" onchange="validateTotalInputs()" onkeyup="validateTotalInputs()" id="Others" name="Others" class="form-control form-control-sm text-right" autofocus>
                 </td>
              </tr>
              <tr>
               <td>Materials Total</td>
               <td>
                  <input type="number" step="any" value="{{ $paymentOrder->MaterialTotal }}" onchange="validateTotalInputs()" onkeyup="validateTotalInputs()" id="MaterialsTotal" name="MaterialsTotal" class="form-control form-control-sm text-right" autofocus>
               </td>
            </tr>
           </table>

           <div class="divider"></div>

           <table class="table table-hover table-borderless table-sm">
              <tr>
                 <th>Local F. Tax</th>
                 <th>
                    <input type="number" step="any" value="{{ $paymentOrder->LocalFTax }}" id="LocalFTax" name="LocalFTax" class="form-control form-control-sm text-right" autofocus>
                 </th>
              </tr>
              <tr>
                 <th>Sub-total w/o VAT</th>
                 <th>
                    <input type="number" step="any" value="{{ $paymentOrder->SubTotal }}" id="SubTotal" name="SubTotal" class="form-control form-control-sm text-right" autofocus>
                 </th>
              </tr>
              <tr>
                 <th>VAT</th>
                 <th>
                    <input type="number" step="any" value="{{ $paymentOrder->VAT }}" id="VAT" name="VAT" class="form-control form-control-sm text-right" autofocus>
                 </th>
              </tr>
              <tr>
                 <th>Others</th>
                 <th>
                    <input type="number" step="any" value="{{ $paymentOrder->OthersTotal }}" onkeyup="validateTotalInputs()" id="OthersTotal" name="OthersTotal" class="form-control form-control-sm text-right" autofocus>
                 </th>
              </tr>
              <tr>
                 <th><h4>TOTAL</h4></th>
                 <th>
                    <input type="number" step="any" value="{{ $paymentOrder->OverAllTotal }}" style="font-size: 1.3em;" id="OverAllTotal" name="OverAllTotal" class="form-control form-control-sm text-right" autofocus>
                 </th>
              </tr>
           </table>
        </div>
     </div>
  </div>

   <div class="col-lg-12" style="margin-bottom: 15px;">
      <button class="btn btn-danger float-right" onclick="savePaymentOrder()"><i class="fas fa-dollar-sign ico-tab"></i>Update Payment Order</button>
      <div id="loader" class="spinner-border text-danger float-right gone" style="margin-right: 10px;" role="status">
         <span class="sr-only">Loading...</span>
     </div>
  </div>
</div>

{{-- MODAL FOR ADDING ITEMS --}}
@include('service_connections.modal_payment_order_items')
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

         $('#modal-add-items').on('hidden.bs.modal', function() {
            $('#ItemQuantity').focus()
         });

         $('#CostCenterDescription').val($('#CostCenter option:selected', this).attr('data_desc'))
         $('#meter-CostCenterDescription').val($('#meter-CostCenter option:selected', this).attr('data_desc'))

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

         /** 
          * FEES AREA
          * =================================
          */
      })

      /**
       *  MATERIAL ITEMS
       * ======================================
       */
      function getTotalItemCost(qty, cost) {
         var total = qty * cost

         return total
      }

      function addToList() {
         if (jQuery.isEmptyObject($('#ItemQuantity').val()) | jQuery.isEmptyObject($('#ItemCode').val()) | jQuery.isEmptyObject($('#ItemDescription').val())) {
            Toast.fire({
               icon : 'warning',
               text : 'please select item first!'
            })
         } else {
            $('#items-list tbody').append(addRowItem($('#ItemCode').val(), $('#ItemDescription').val(), $('#ItemQuantity').val(), selectedUOM, selectedItemCost, $('#ItemTotalCost').val(), selectedUnitPrice))
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
                  "<td class='text-right'>" + Math.round((parseFloat(uprice) + Number.EPSILON) * 100) / 100 + "</td>" +
                  "<td class='text-right'>" + Math.round((parseFloat(selectedUnitPrice) + Number.EPSILON) * 100) / 100 + "</td>" +
                  "<td class='text-right text-primary'>" + tcost + "</td>" +
                  "<td>" + 
                     "<button onclick='removeItem(`" + id + "`, `NEW`)' class='btn btn-xs btn-link text-danger' style='margin-left: 10px;'><i class='fas fa-trash'></i></button>" +
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
                  ItemUnitPrice : $('td', this).eq(5).text(),
                  ItemSalesPrice : $('td', this).eq(6).text(),
                  ItemTotalCost : $('td', this).eq(7).text().trim(),
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
            return Math.round((parseFloat(amount) + Number.EPSILON) * 100) / 100
         }
      }

      function getTotalFTax() {
         var overheadExpenses = getLocalFTax($('#OverheadExpenses').val())
         var transformerRentalFees = getLocalFTax($('#TransformerRentalFees').val())
         var apprehension = getLocalFTax($('#Apprehension').val())
         var serviceFee = getLocalFTax($('#ServiceFee').val())
         var materialsTotal = getLocalFTax($('#MaterialsTotal').val())
         var others = getLocalFTax($('#Others').val())

         var total = overheadExpenses + transformerRentalFees + apprehension + serviceFee + materialsTotal + others     

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
         var materialsTotal = getInputAmount($('#MaterialsTotal').val())

         var total = materialDeposit + overheadExpenses + transformerRentalFees + apprehension + customerDeposit + ciac + serviceFee + others + othersTotal + materialsTotal  

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function getTotalVat() {
         var overheadExpenses = (getInputAmount($('#OverheadExpenses').val()) + getLocalFTax($('#OverheadExpenses').val())) * .12
         var transformerRentalFees = (getInputAmount($('#TransformerRentalFees').val()) + getLocalFTax($('#TransformerRentalFees').val())) * .12
         var apprehension = (getInputAmount($('#Apprehension').val()) + getLocalFTax($('#Apprehension').val())) * .12
         var serviceFee = (getInputAmount($('#ServiceFee').val()) + getLocalFTax($('#ServiceFee').val())) * .12
         var materialsTotal = (getInputAmount($('#MaterialsTotal').val()) + getLocalFTax($('#MaterialsTotal').val())) * .12

         var total = overheadExpenses + transformerRentalFees + apprehension + serviceFee + materialsTotal     

         return Math.round((parseFloat(total) + Number.EPSILON) * 100) / 100
      }

      function getOverAllTotal() {
         var fTax = getInputAmount($('#LocalFTax').val())
         var subTotal = getInputAmount($('#SubTotal').val())
         var vat = getInputAmount($('#VAT').val())

         return Math.round((parseFloat(fTax + subTotal + vat) + Number.EPSILON) * 100) / 100
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
         var acctNo = $('#AccountNumber').val()
         if (jQuery.isEmptyObject(acctNo)) {
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
                  MeterRequestedBy : $('#meter-RequestedBy').val(),
                  MeterInvoiceNo : $('#meter-InvoiceNo').val(),
                  MeterCustomerName : $('#meter-CustomerName').val(),
                  MeterTypeOfServiceId : $('#meter-TypeOfServiceId').val(),
                  MeterEntryNo : $('#meter-EntryNo').val(),
                  AccountNumber : acctNo,
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

      function removeItem(id, status) {
         Swal.fire({
            title: 'Do you want to delete this item?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: `No`,
         }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               if (status == 'OLD') {
                  $.ajax({
                     url : "{{ route('warehouseItems.remove-item') }}",
                     type : "GET",
                     data : {
                        id : id,
                     },
                     success : function(res) {
                        $('#' + id).remove()
                        $('#MaterialsTotal').val(getItemTotal())
                        $('#MaterialsTotal').change()
                        validateTotalInputs()
                        clearSelection()
                        Toast.fire({
                           icon : 'success',
                           text : 'item removed!'
                        })
                     },
                     error : function(err) {
                        console.log(err)
                        Toast.fire({
                           icon : 'error',
                           text : 'error removing item!'
                        })
                     }
                  })
               } else {
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
            }
         })
         
      }
    </script>
@endpush