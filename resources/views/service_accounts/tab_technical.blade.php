<div class="card card-outline {{ $serviceAccounts->AccountStatus=='ACTIVE' ? 'card-success' : 'card-danger' }}">
    <div class="card-header border-0">
        <span class="card-title">Active Meter Info</span>

        <div class="card-tools">
            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                @if ($meters != null)
                    <a href="{{ route('billingMeters.edit', [$meters->id]) }}" class="btn btn-tool text-success" title="Update meter data"><i class="fas fa-pen"></i></a>
                @endif
                <a href="#" class="btn btn-tool text-primary" title="Change Meter"><i class="fas fa-random"></i></a>
                <button class="btn btn-tool"  data-toggle="modal" data-target="#modal-meter-logs" title="Meter history"><i class="fas fa-history"></i></button>
            @endif
        </div>
    </div>
    <div class="card-body table-responsive px-0">
        @if ($meters != null)
            <table class="table table-sm table-hover">
                <tr>
                    <td class="text-muted">Serial Number</td>
                    <th>{{ $meters->SerialNumber }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Brand</td>
                    <th>{{ $meters->Brand }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Model</td>
                    <th>{{ $meters->Model }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Seal Number</td>
                    <th>{{ $meters->SealNumber }}</th>
                </tr>
                <tr>
                    <td class="text-muted">Date of Installation</td>
                    <th>{{ $meters->ConnectionDate==null ? '-' : date('F d, Y', strtotime($meters->ConnectionDate)) }}</th>
                </tr>                
                <tr>
                    <th>Multiplier</th>
                    <th>{{ $meters->Multiplier }}</th>
                </tr>
            </table>
        @else
            <p class="center-text">No Meter data recorded.</p>
        @endif
        
    </div>
</div>

<div class="card card-outline {{ $serviceAccounts->AccountStatus=='ACTIVE' ? 'card-success' : 'card-danger' }}">
    <div class="card-header border-0">
        <span class="card-title">Active Transformer Info</span>

        <div class="card-tools">
            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator']))
                @if ($transformer != null)
                <a href="{{ route('billingTransformers.edit', [$transformer->id]) }}" class="btn btn-tool text-success" title="Update transformer data"><i class="fas fa-pen"></i></a>
                @endif
                <a href="#" class="btn btn-tool text-primary" title="Change Transformer"><i class="fas fa-random"></i></a>
                <a href="#" class="btn btn-tool" title="Transformer history"><i class="fas fa-history"></i></a>
            @endif
        </div>
    </div>
    <div class="card-body table-responsive px-0">
        @if ($transformer != null)
            <table class="table table-hover table-sm">
                <tr>
                    <td>Transformer Number</td>
                    <th>{{ $transformer->TransformerNumber }}</th>
                </tr>
                <tr>
                    <td>Rating</td>
                    <th>{{ $transformer->Rating }} kVA</th>
                </tr>
                <tr>
                    <td>Rental Fee</td>
                    <th>{{ $transformer->RentalFee==null ? 'none' : 'P ' . number_format($transformer->RentalFee, 2) }}</th>
                </tr>
            </table>
        @else
            <p class="center-text">No Transformer data recorded.</p>
        @endif
    </div>
</div>

@include('service_accounts.change_meter_history')