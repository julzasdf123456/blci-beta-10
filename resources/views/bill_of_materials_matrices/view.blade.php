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
                    <h4 class="m-0">Bill of Materials</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Bill of Materials</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <span class="float-right">
                <a href="{{ route('billOfMaterialsMatrices.download-bill-of-materials', [$serviceConnection->id]) }}" class="btn btn-sm btn-success" title="Download Excel File"><i class="fas fa-download"></i></a>
                <a href="" class="btn btn-sm btn-default" title="Print"><i class="fas fa-print"></i></a>
            </span>
        </div>
    </div>
    <br>

    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            Issued From
            <address>
                <strong>{{ env('APP_COMPANY_ABRV') }}</strong><br>
                Street, Barangay, Town, Province<br>
                Tin No Here: #########<br>
                Phone Here: ########<br>
                Email Here: ##########
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            Issued To
            <address>
                <strong>{{ $serviceConnection->ServiceAccountName }}</strong><br>
                {{ ServiceConnections::getAddress($serviceConnection) }}<br>
                Phone: {{ $serviceConnection->ContactNumber }}<br>
                Building Type: {{ $serviceConnection->BuildingType }}<br>
                Date of Application: {{ date('F d, Y', strtotime($serviceConnection->DateOfApplication)) }}
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <br>
            <b >Account: </b><span id="scId">{{ $serviceConnection->id }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <th>NEA Code</th>
                    <th>Description</th>
                    <th class="text-right">Unit Cost</th>
                    <th class="text-right">Project Requirements</th>
                    <th class="text-right">Extended Cost</th>
                </thead>
                <tbody>
                    @php
                        $total = 0.0;
                    @endphp
                    @foreach ($billOfMaterials as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->Description }}</td>
                            <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                            <td class="text-right">{{ $item->ProjectRequirements }}</td>
                            <td class="text-right">{{ number_format($item->ExtendedCost, 2) }}</td>
                        </tr>
                        @php
                            $total += doubleval($item->ExtendedCost);
                        @endphp
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong>{{ number_format($total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script type="text/javascript">
    </script>
@endpush