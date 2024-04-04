@if($paymentOrder == null)
    <p class="text-center"><i>No payment transactions recorded!</i></p>
    @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Service Connection Assessor'])) 
        <a href="{{ route('serviceConnections.payment-order', [$serviceConnections->id]) }}" class="btn btn-primary btn-sm" title="Create Payment Order">
            <i class="fas fa-plus ico-tab"></i>
            Create Payment Order</a>
    @endif
@else
<div class="row p-3">
    {{-- MATERIAL ITEMS --}}
    @if ($whHead != null)
    <div class="col-lg-12">
        <div class="card">
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
                       @include('service_connections.tab_invoice_materials')
                    </div>
     
                    {{-- METER --}}
                    <div class="tab-pane" id="meter">
                       @include('service_connections.tab_invoice_meters')
                    </div>
                </div>            
            </div>
            <div class="card-footer">
               <a href="{{ route('serviceConnections.update-payment-order', [$serviceConnections->id]) }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-pen ico-tab"></i>Edit Payment Order</a>
               <a href="{{ route('serviceConnections.print-material-and-meters', [$serviceConnections->id]) }}" class="btn btn-primary-skinny btn-sm float-right ico-tab-mini"><i class="fas fa-print ico-tab"></i>Print Both</a>
               <a href="{{ route('serviceConnections.print-order-materials', [$serviceConnections->id]) }}" class="btn btn-primary-skinny btn-sm float-right ico-tab-mini"><i class="fas fa-print ico-tab"></i>Print Materials</a>
               <a href="{{ route('serviceConnections.print-order-meters', [$serviceConnections->id]) }}" class="btn btn-primary-skinny btn-sm float-right ico-tab-mini"><i class="fas fa-print ico-tab"></i>Print Meter</a>
            </div>
        </div>
    </div>
    @endif
    
    {{-- FEES --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
               <span class="card-title">Fees</span>
            </div>
            <div class="card-body table-responsive p-0">
               <table class="table table-sm table-borderless table-hover">
                  <tr>
                     <td>Material Deposit</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->MaterialDeposit) ? number_format($paymentOrder->MaterialDeposit, 2) : $paymentOrder->MaterialDeposit }}</strong></td>
                  </tr>
                  <tr>
                     <td>Over-head Expenses</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->OverheadExpenses) ? number_format($paymentOrder->OverheadExpenses, 2) : $paymentOrder->OverheadExpenses }}</strong></td>
                  </tr>
                  <tr>
                     <td>Transformer Rental Fees</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->TransformerRentalFees) ? number_format($paymentOrder->TransformerRentalFees, 2) : $paymentOrder->TransformerRentalFees }}</strong></td>
                  </tr>
                  <tr>
                     <td>Apprehension</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->Apprehension) ? number_format($paymentOrder->Apprehension, 2) : $paymentOrder->Apprehension }}</strong></td>
                  </tr>
                  <tr>
                     <td>Customer Deposit</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->CustomerDeposit) ? number_format($paymentOrder->CustomerDeposit, 2) : $paymentOrder->CustomerDeposit }}</strong></td>
                  </tr>
                  <tr>
                     <td>CIAC</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->CIAC) ? number_format($paymentOrder->CIAC, 2) : $paymentOrder->CIAC }}</strong></td>
                  </tr>
                  <tr>
                     <td>Service Fee</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->ServiceFee) ? number_format($paymentOrder->ServiceFee, 2) : $paymentOrder->ServiceFee }}</strong></td>
                  </tr>
                  <tr>
                     <td>Sale Of Materials</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->SaleOfMaterials) ? number_format($paymentOrder->SaleOfMaterials, 2) : $paymentOrder->SaleOfMaterials }}</strong></td>
                  </tr>
                  <tr>
                     <td>Others</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->OthersTotal) ? number_format($paymentOrder->OthersTotal, 2) : $paymentOrder->OthersTotal }}</strong></td>
                  </tr>
                  <tr>
                     <td>Materials Total</td>
                     <td class="text-right"><strong>P {{ is_numeric($paymentOrder->MaterialTotal) ? number_format($paymentOrder->MaterialTotal, 2) : $paymentOrder->MaterialTotal }}</strong></td>
                  </tr>
               </table>
            </div>
        </div>
    </div>

    {{-- OVERALL TOTAL --}}
    <div class="col-lg-6">
        <div class="card {{ $serviceConnections->ORNumber != null ? 'card-success' : 'card-danger' }}">
            <div class="card-header">
               <span class="card-title">Summary</span>
            </div>
            <div class="card-body table-responsive">
               <table class="table table-hover table-borderless table-sm">
                  <tr>
                     <th>Local F. Tax</th>
                     <th class="text-right">P {{ is_numeric($paymentOrder->LocalFTax) ? number_format($paymentOrder->LocalFTax, 2) : $paymentOrder->LocalFTax }}</th>
                  </tr>
                  <tr>
                     <th>Sub-total w/o VAT</th>
                     <th class="text-right">P {{ is_numeric($paymentOrder->SubTotal) ? number_format($paymentOrder->SubTotal, 2) : $paymentOrder->SubTotal }}</th>
                  </tr>
                  <tr>
                     <th>VAT</th>
                     <th class="text-right">P {{ is_numeric($paymentOrder->VAT) ? number_format($paymentOrder->VAT, 2) : $paymentOrder->VAT }}</th>
                  </tr>
                  <tr>
                     <th><h4>OVERALL TOTAL</h4></th>
                     <th><h2 class="text-right  {{ $serviceConnections->ORNumber != null ? 'text-success' : 'text-danger' }}">P {{ is_numeric($paymentOrder->OverAllTotal) ? number_format($paymentOrder->OverAllTotal, 2) : $paymentOrder->OverAllTotal }}</h2></th>
                  </tr>
               </table>

               @if ($serviceConnections->ORNumber != null)
                    <div class="divider"></div>
                    <table class="table table-hover table-borderless table-sm">
                        <tr>
                            <td>OR Number: </td>
                            <td class="text-right"><strong>{{ $serviceConnections->ORNumber }}</strong></td>
                        </tr>
                        <tr>
                            <td>Payment Date: </td>
                            <td class="text-right"><strong>{{ $serviceConnections->ORDate != null ? date('F d, Y', strtotime($serviceConnections->ORDate)) : '-' }}</strong></td>
                        </tr>
                    </table>
               @endif
            </div>
        </div>
    </div>
</div>
@endif