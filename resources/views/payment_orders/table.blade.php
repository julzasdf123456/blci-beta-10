<div class="table-responsive">
    <table class="table" id="paymentOrders-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Materialdeposit</th>
        <th>Transformerrentalfees</th>
        <th>Apprehension</th>
        <th>Overheadexpenses</th>
        <th>Ciac</th>
        <th>Servicefee</th>
        <th>Customerdeposit</th>
        <th>Meterquantity</th>
        <th>Meterunitprice</th>
        <th>Meteramount</th>
        <th>Twistedwire6Quantity</th>
        <th>Twistedwire6Unitprice</th>
        <th>Twistedwire6Amount</th>
        <th>Strandedwire8Quantity</th>
        <th>Strandedwire8Unitprice</th>
        <th>Strandedwire8Amount</th>
        <th>Saleofitemsquantity</th>
        <th>Saleofitemsunitprice</th>
        <th>Saleofitemsamount</th>
        <th>Compressiontapquantity</th>
        <th>Compressiontapunitprice</th>
        <th>Compressiontapamount</th>
        <th>Plyboardquantity</th>
        <th>Plyboardunitprice</th>
        <th>Plyboardamount</th>
        <th>Stainlessbucklequantity</th>
        <th>Stainlessbuckleunitprice</th>
        <th>Stainlessbuckleamount</th>
        <th>Electricaltapequantity</th>
        <th>Electricaltapeunitprice</th>
        <th>Electricaltapeamount</th>
        <th>Stainlessstrapquantity</th>
        <th>Stainlessstrapunitprice</th>
        <th>Stainlessstrapamount</th>
        <th>Metalwoodscrewquantity</th>
        <th>Metalwoodscrewunitprice</th>
        <th>Metalwoodscrewamount</th>
        <th>Totalsales</th>
        <th>Others</th>
        <th>Localftax</th>
        <th>Subtotal</th>
        <th>Vat</th>
        <th>Otherstotal</th>
        <th>Overalltotal</th>
        <th>Ornumber</th>
        <th>Ordate</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paymentOrders as $paymentOrder)
            <tr>
                <td>{{ $paymentOrder->ServiceConnectionId }}</td>
            <td>{{ $paymentOrder->MaterialDeposit }}</td>
            <td>{{ $paymentOrder->TransformerRentalFees }}</td>
            <td>{{ $paymentOrder->Apprehension }}</td>
            <td>{{ $paymentOrder->OverheadExpenses }}</td>
            <td>{{ $paymentOrder->CIAC }}</td>
            <td>{{ $paymentOrder->ServiceFee }}</td>
            <td>{{ $paymentOrder->CustomerDeposit }}</td>
            <td>{{ $paymentOrder->MeterQuantity }}</td>
            <td>{{ $paymentOrder->MeterUnitPrice }}</td>
            <td>{{ $paymentOrder->MeterAmount }}</td>
            <td>{{ $paymentOrder->TwistedWire6Quantity }}</td>
            <td>{{ $paymentOrder->TwistedWire6UnitPrice }}</td>
            <td>{{ $paymentOrder->TwistedWire6Amount }}</td>
            <td>{{ $paymentOrder->StrandedWire8Quantity }}</td>
            <td>{{ $paymentOrder->StrandedWire8UnitPrice }}</td>
            <td>{{ $paymentOrder->StrandedWire8Amount }}</td>
            <td>{{ $paymentOrder->SaleOfItemsQuantity }}</td>
            <td>{{ $paymentOrder->SaleOfItemsUnitPrice }}</td>
            <td>{{ $paymentOrder->SaleOfItemsAmount }}</td>
            <td>{{ $paymentOrder->CompressionTapQuantity }}</td>
            <td>{{ $paymentOrder->CompressionTapUnitPrice }}</td>
            <td>{{ $paymentOrder->CompressionTapAmount }}</td>
            <td>{{ $paymentOrder->PlyboardQuantity }}</td>
            <td>{{ $paymentOrder->PlyboardUnitPrice }}</td>
            <td>{{ $paymentOrder->PlyboardAmount }}</td>
            <td>{{ $paymentOrder->StainlessBuckleQuantity }}</td>
            <td>{{ $paymentOrder->StainlessBuckleUnitPrice }}</td>
            <td>{{ $paymentOrder->StainlessBuckleAmount }}</td>
            <td>{{ $paymentOrder->ElectricalTapeQuantity }}</td>
            <td>{{ $paymentOrder->ElectricalTapeUnitPrice }}</td>
            <td>{{ $paymentOrder->ElectricalTapeAmount }}</td>
            <td>{{ $paymentOrder->StainlessStrapQuantity }}</td>
            <td>{{ $paymentOrder->StainlessStrapUnitPrice }}</td>
            <td>{{ $paymentOrder->StainlessStrapAmount }}</td>
            <td>{{ $paymentOrder->MetalWoodScrewQuantity }}</td>
            <td>{{ $paymentOrder->MetalWoodScrewUnitPrice }}</td>
            <td>{{ $paymentOrder->MetalWoodScrewAmount }}</td>
            <td>{{ $paymentOrder->TotalSales }}</td>
            <td>{{ $paymentOrder->Others }}</td>
            <td>{{ $paymentOrder->LocalFTax }}</td>
            <td>{{ $paymentOrder->SubTotal }}</td>
            <td>{{ $paymentOrder->VAT }}</td>
            <td>{{ $paymentOrder->OthersTotal }}</td>
            <td>{{ $paymentOrder->OverAllTotal }}</td>
            <td>{{ $paymentOrder->ORNumber }}</td>
            <td>{{ $paymentOrder->ORDate }}</td>
            <td>{{ $paymentOrder->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['paymentOrders.destroy', $paymentOrder->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('paymentOrders.show', [$paymentOrder->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('paymentOrders.edit', [$paymentOrder->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
