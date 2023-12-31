@if (Route::currentRouteName() == "serviceAccounts.show")
    {{-- Account --}}
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="bills-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Account <i class="fas fa-caret-down ico-tab-mini-left"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="bills-menu">
            <a href="{{ route('serviceAccounts.update-step-one', [$serviceAccounts->id]) }}" class="dropdown-item" title="Update Consumer Info">Update Account Details</a>
            <div class="dropdown-divider"></div>
            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                <button class="dropdown-item" id="change-name" data-toggle="modal" data-target="#modal-change-name" title="Change Name">Change Name</button>
            @endif
            <button class="dropdown-item" data-toggle="modal" data-target="#modal-change-name-history" title="Change Name History">Change Name History</button>
            <button class="dropdown-item" data-toggle="modal" data-target="#modal-relocation-history" title="Location History (Relocations)">Relocation History</button>
            <button class="dropdown-item" data-toggle="modal" data-target="#modal-view-map" title="View in map">View Account in Map</button>
        </div>
    </li>

    {{-- Bills --}}
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="bills-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Bills <i class="fas fa-caret-down ico-tab-mini-left"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="bills-menu">
            <a class="dropdown-item" href="{{ route('readings.manual-reading-console', [$serviceAccounts->id]) }}">Manual Billing</a>
            <button class="dropdown-item" onclick="showMeterAccuracyModal()">Meter Accuracy Adjustment</button>
            <button class="dropdown-item" data-toggle="modal" data-target="#modal-reading-history">View Reading History</button>
            <button class="dropdown-item" data-toggle="modal" data-target="#modal-ledger-history">View Full Ledger</button>
            <button class="dropdown-item" data-toggle="modal" data-target="#modal-print-ledger">Print Ledger</button>
            {{-- <div class="dropdown-divider"></div> --}}
        </div>
    </li>
@endif