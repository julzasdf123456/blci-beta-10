@php
    
use Illuminate\Support\Facades\Auth;

@endphp

<!-- MEMBERSHIP MENU -->
{{-- @canany(['Super Admin', 'view membership'])
    <li class="nav-header">CRM</li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-users nav-icon text-success"></i>
            <p>
                Membership
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @canany(['Super Admin', 'view membership'])
                <li class="nav-item">
                    <a href="{{ route('memberConsumers.index') }}"
                    class="nav-link {{ Request::is('memberConsumers.index*') ? 'active' : '' }}">
                    <i class="fas fa-street-view nav-icon text-success"></i><p>Member Consumers</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'create membership'])
                <li class="nav-item">
                    <a href="{{ route('memberConsumers.create') }}"
                    class="nav-link {{ Request::is('memberConsumers.create*') ? 'active' : '' }}">
                    <i class="fas fa-user-plus nav-icon text-success"></i><p>Register New MCO</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'update membership'])
                <li class="nav-header">                
                    Settings
                </li>

                <li class="nav-item">
                    <a href="{{ route('memberConsumerTypes.index') }}"
                    class="nav-link {{ Request::is('memberConsumerTypes*') ? 'active' : '' }}">
                    <i class="fas fa-code-branch nav-icon text-success"></i><p>Consumer Types</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('memberConsumerChecklistsReps.index') }}"
                    class="nav-link {{ Request::is('memberConsumerChecklistsReps*') ? 'active' : '' }}">
                    <i class="fas fa-check nav-icon text-success"></i><p>Checklists</p>
                    </a>
                </li>
            @endcanany
        </ul>
    </li>    
@endcanany --}}


<!-- SERVICE CONNECTION MENU -->
@canany(['Super Admin', 'sc view'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-plug nav-icon text-warning"></i>
            <p>
                Service Connections
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @canany(['Super Admin', 'sc view'])
                <li class="nav-item">
                    <a href="{{ route('serviceConnections.dashboard') }}"
                    class="nav-link {{ Request::is('serviceConnections.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon text-warning"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'sc view'])
                <li class="nav-item">
                    <a href="{{ route('serviceConnections.index') }}"
                    class="nav-link {{ Request::is('serviceConnections*') ? 'active' : '' }}">
                        <i class="fas fa-bolt nav-icon text-warning"></i>
                        <p>All Applications</p>
                    </a>
                </li>
            @endcanany

            @canany(['Super Admin', 'sc create'])
                <li class="nav-item">
                    <a href="{{ route('serviceConnections.create_new') }}"
                    class="nav-link {{ Request::is('serviceConnections.create_new') ? 'active' : '' }}">
                        <i class="fas fa-plus nav-icon text-warning"></i>
                        <p>New Application</p>
                    </a>
                </li>
            @endcanany

            @if (Auth::user()->hasRole('Metering Personnel'))
            <li class="nav-item">
                <a href="{{ route('serviceConnectionMtrTrnsfrmrs.assigning') }}"
                class="nav-link {{ Request::is('serviceConnectionMtrTrnsfrmrs.assigning') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon text-warning"></i>
                    <p>Assign Meters
                        <span id="assign-badge-count" class="right badge badge-danger">0</span>
                    </p>
                </a>
            </li>
            @endif

            @canany(['Super Admin', 'sc view'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Monitoring
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.daily-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.daily-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check nav-icon text-warning"></i><p>Daily Monitor</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.inspection-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.inspection-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Inspections</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.for-payment') }}"
                        class="nav-link {{ Request::is('serviceConnections.for-payment*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>For Payment</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.for-energization') }}"
                        class="nav-link {{ Request::is('serviceConnections.for-energization*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>For Energization</p>
                        </a>
                    </li>

                    {{-- FLOW --}}
                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionMtrTrnsfrmrs.assigning') }}"
                        class="nav-link {{ Request::is('serviceConnectionMtrTrnsfrmrs.assigning*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon text-warning"></i><p>For Meter Assigning</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.manual-energization') }}"
                        class="nav-link {{ Request::is('serviceConnections.manual-energization*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Manual Energization</p>
                        </a>
                    </li>
{{-- 
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.energization') }}"
                        class="nav-link {{ Request::is('serviceConnections.energization*') ? 'active' : '' }}">
                        <i class="fas fa-check nav-icon text-warning"></i><p>For Energization</p>
                        </a>
                    </li> --}}
                </ul>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc create'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Reports
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.applications-report') }}"
                        class="nav-link {{ Request::is('serviceConnections.applications-report*') ? 'active' : '' }}">
                        <i class="fas fa-file-import nav-icon text-warning"></i><p>Applications</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.energization-report') }}"
                        class="nav-link {{ Request::is('serviceConnections.energization-report*') ? 'active' : '' }}">
                        <i class="fas fa-charging-station nav-icon text-warning"></i><p>Energization</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.metering-installation') }}"
                        class="nav-link {{ Request::is('serviceConnections.metering-installation*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon text-warning"></i><p>Meter Installation</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.summary-report') }}"
                        class="nav-link {{ Request::is('serviceConnections.summary-report*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list nav-icon text-warning"></i><p>Summary</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.detailed-summary') }}"
                        class="nav-link {{ Request::is('serviceConnections.detailed-summary*') ? 'active' : '' }}">
                        <i class="fas fa-list nav-icon text-warning"></i><p>Detailed Summary</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.mriv') }}" title="Material Request Issuance Voucher"
                        class="nav-link {{ Request::is('serviceConnections.mriv*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>MRIV</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.sevice-connections-report') }}" title="Service Connections Energization Report"
                        class="nav-link {{ Request::is('serviceConnections.sevice-connections-report*') ? 'active' : '' }}">
                        <i class="fas fa-circle nav-icon text-warning"></i><p>Service Connections</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            @canany(['Super Admin', 'sc settings'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    {{-- <i class="fas fa-cogs"></i> --}}
                    <p>
                        Settings
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionAccountTypes.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionAccountTypes*') ? 'active' : '' }}">
                        <i class="fas fa-code-branch nav-icon text-warning"></i><p>Classification of Service</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceAppliedFors.index') }}"
                           class="nav-link {{ Request::is('serviceAppliedFors*') ? 'active' : '' }}">
                           <i class="fas fa-circle nav-icon text-warning"></i><p>Services Applied For</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionMatPayables.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionMatPayables*') ? 'active' : '' }}">
                        <i class="fas fa-hammer nav-icon text-warning"></i><p>Material Payables</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionPayParticulars.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionPayParticulars*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart nav-icon text-warning"></i><p>Payment Particulars</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('serviceConnectionChecklistsReps.index') }}"
                        class="nav-link {{ Request::is('serviceConnectionChecklistsReps*') ? 'active' : '' }}">
                        <i class="fas fa-check nav-icon text-warning"></i><p>Checklists</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            {{-- MATERIALS AND STRUCTURES --}}
            @canany(['Super Admin', 'sc create'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    {{-- <i class="fas fa-toolbox nav-icon"></i> --}}
                    <p>
                        Mat. & Structures
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('structures.index') }}"
                        class="nav-link {{ Request::is('structures*') ? 'active' : '' }}">
                        <i class="fas fa-draw-polygon nav-icon text-warning"></i><p>Structures</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('materialAssets.index') }}"
                        class="nav-link {{ Request::is('materialAssets*') ? 'active' : '' }}">
                        <i class="fas fa-plug nav-icon text-warning"></i><p>Material Assets</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('transformerIndices.index') }}"
                        class="nav-link {{ Request::is('transformerIndices*') ? 'active' : '' }}">
                        <i class="fas fa-car-battery nav-icon text-warning"></i><p>Transformer Index</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('poleIndices.index') }}"
                        class="nav-link {{ Request::is('poleIndices*') ? 'active' : '' }}">
                        <i class="fas fa-cross nav-icon text-warning"></i><p>Pole Index</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('spanningIndices.index') }}"
                        class="nav-link {{ Request::is('spanningIndices*') ? 'active' : '' }}">
                        <i class="fas fa-network-wired nav-icon text-warning"></i>
                        <p>Spanning Index</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('specialEquipmentMaterials.index') }}"
                        class="nav-link {{ Request::is('specialEquipmentMaterials*') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt nav-icon text-warning"></i><p>Special Eq. Materials</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('preDefinedMaterials.index') }}"
                        class="nav-link {{ Request::is('preDefinedMaterials*') ? 'active' : '' }}">
                            <i class="fas fa-plug nav-icon text-warning"></i><p>Pre-Defined Materials</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            {{-- @canany(['Super Admin', 'sc view'])
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <p>
                        Others
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('serviceConnections.fleet-monitor') }}"
                        class="nav-link {{ Request::is('serviceConnections.fleet-monitor*') ? 'active' : '' }}">
                        <i class="fas fa-street-view nav-icon text-warning"></i><p>Fleet Monitoring</p>
                        </a>
                    </li>

                </ul>
            </li>
            @endcanany --}}
            
            @canany(['Super Admin', 'sc delete'])
            <li class="nav-item">
                <a href="{{ route('serviceConnections.trash') }}"
                class="nav-link {{ Request::is('serviceConnections.trash*') ? 'active' : '' }}">
                <i class="fas fa-trash nav-icon text-warning"></i><p>Trash</p>
                </a>
            </li>
            @endcanany
        </ul>
    </li>
@endcanany

{{-- TICKETS --}}
@canany(['Super Admin', 'tickets view'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-ambulance nav-icon text-danger"></i>
            <p>
                Tickets
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('tickets.dashboard') }}"
                class="nav-link {{ Request::is('tickets.dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon text-danger"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.index') }}"
                class="nav-link {{ Request::is('tickets.index*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list nav-icon text-danger"></i><p>All Tickets</p>
                </a>
            </li>
            @canany(['Super Admin', 'tickets create'])
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="fas fa-plus-circle nav-icon text-danger"></i>
                    <p>
                        Create Ticket
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('tickets.create-select') }}"
                        class="nav-link {{ Request::is('tickets.create-select*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Ordinary Complain</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.change-meter') }}"
                        class="nav-link {{ Request::is('tickets.change-meter*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Change Meter</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.disconnection-assessments') }}"
                        class="nav-link {{ Request::is('tickets.disconnection-assessments*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon text-danger"></i><p>Disconnection</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany
            @canany(['Super Admin', 'tickets edit'])
            <li class="nav-header">                
                Assessments 
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.assessments-change-meter') }}"
                class="nav-link {{ Request::is('tickets.assessments-change-meter*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Change Meter Requests</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.assessments-ordinary-ticket') }}"
                class="nav-link {{ Request::is('tickets.assessments-ordinary-ticket*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>Crew Assigning</p>
                </a>
            </li>
            @endcanany
            @canany(['Super Admin', 'tickets create', 'tickets edit'])
            <li class="nav-header">                
                Reports
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.ticket-summary-report') }}"
                class="nav-link {{ Request::is('tickets.ticket-summary-report*') ? 'active' : '' }}">
                    <i class="fas fa-file nav-icon text-danger"></i><p>Ticket Summary</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.kps-summary-report') }}"
                class="nav-link {{ Request::is('tickets.kps-summary-report*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-danger"></i><p>KPS Summary (NEA)</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tickets.ticket-tally') }}"
                class="nav-link {{ Request::is('tickets.ticket-tally*') ? 'active' : '' }}">
                    <i class="fas fa-list nav-icon text-danger"></i><p>Ticket Tally</p>
                </a>
            </li>
            <li class="nav-header">                
                Settings and Others 
            </li>
            <li class="nav-item">
                <a href="{{ route('ticketsRepositories.index') }}"
                   class="nav-link {{ Request::is('ticketsRepositories*') ? 'active' : '' }}">
                   <i class="fas fa-check-circle nav-icon text-danger"></i><p>Ticket Types</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tickets.trash') }}"
                   class="nav-link {{ Request::is('tickets.trash*') ? 'active' : '' }}">
                   <i class="fas fa-trash nav-icon text-danger"></i><p>Trash</p>
                </a>
            </li>
            @endcanany
        </ul>
    </li>
    
@endcanany

@if (env('APP_AREA_CODE')=='15') 
@canany(['Super Admin'])
<li class="nav-item">
    <a href="{{ route('events.index') }}"
    class="nav-link {{ Request::is('events*') ? 'active' : '' }}">
    <i class="fas fa-check-circle nav-icon text-primary"></i><p>Events</p>
    </a>
</li>
@endcanany 
@endif

{{-- DAMAGE ASSESSMENT --}}
{{-- @canany(['Super Admin', 'view damage monitor'])
    <li class="nav-item">
        <a href="{{ route('damageAssessments.index') }}"
        class="nav-link {{ Request::is('damageAssessments.index*') ? 'active' : '' }}">
            <i class="fas fa-house-damage nav-icon text-default"></i><p>Damage Assessment</p>
        </a>
    </li>    
@endcanany --}}

{{-- SERVICE ACCOUNTS --}}
@canany(['Super Admin', 'billing re-bill'])
    <li class="nav-header">BILLING</li>
    <li class="nav-item">
        <a href="{{ route('bills.dashboard') }}"
           class="nav-link {{ Request::is('bills.dashboard*') ? 'active' : '' }}">                   
           <i class="fas fa-chart-line nav-icon text-primary"></i><p>Dashboard
           </p>
        </a>
    </li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file-invoice-dollar nav-icon text-primary"></i>
            <p>
                Consumer Accounts
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.index') }}"
                   class="nav-link {{ Request::is('serviceAccounts*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-circle nav-icon text-primary"></i><p>All Accounts</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.termed-payment-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.termed-payment-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-list nav-icon text-primary"></i><p>Termed Payment Accounts</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.lifeliners-view') }}"
                   class="nav-link {{ Request::is('serviceAccounts.lifeliners-view') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Lifeliners</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.senior-citizen-view') }}"
                   class="nav-link {{ Request::is('serviceAccounts.senior-citizen-view') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Senior Citizen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.material-deposit-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.material-deposit-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Material Deposits</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.customer-deposit-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.customer-deposit-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Customer Deposits</p>
                </a>
            </li>
            <li class="nav-header">                
                Others 
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.pending-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.pending-accounts*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-alt-slash nav-icon text-primary"></i><p>New Energized</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.manual-account-migration-one') }}"
                   class="nav-link {{ Request::is('serviceAccounts.manual-account-migration-one*') ? 'active' : '' }}">                   
                   <i class="fas fa-user-plus nav-icon text-primary"></i><p>Add New Account
                   </p>                   
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.change-meter-manual') }}"
                   class="nav-link {{ Request::is('serviceAccounts.change-meter-manual*') ? 'active' : '' }}">                   
                   <i class="fas fa-random nav-icon text-primary"></i><p>Change Meter
                   </p>                   
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.relocation-manual') }}"
                   class="nav-link {{ Request::is('serviceAccounts.relocation-manual*') ? 'active' : '' }}">                   
                   <i class="fas fa-map-marked-alt nav-icon text-primary"></i><p>Relocation
                   </p>                   
                </a>
            </li>
        </ul>
    </li>
@endcanany

{{-- BILLS --}}
@canany(['Super Admin', 'billing re-bill'])
    {{-- BILLS --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-wallet nav-icon text-primary"></i>
            <p>
                Bills
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('bills.all-bills') }}"
                   class="nav-link {{ Request::is('bills.all-bills*') ? 'active' : '' }}">
                    <i class="fas fa-list nav-icon text-primary"></i><p>All Bills</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('rates.index') }}"
                   class="nav-link {{ Request::is('rates*') ? 'active' : '' }}">
                    <i class="fas fa-percentage nav-icon text-primary"></i><p>Rate Management</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.unbilled-readings') }}"
                   class="nav-link {{ Request::is('bills.unbilled-readings*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle nav-icon text-primary"></i><p>Unbilled Readings</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.unbilled-no-meter-readers') }}"
                   class="nav-link {{ Request::is('bills.unbilled-no-meter-readers*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Unbilled w/o M.Reader</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.grouped-billing') }}"
                   class="nav-link {{ Request::is('bills.grouped-billing*') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon text-primary"></i><p>Grouped Billing</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.bulk-print-bill') }}"
                   class="nav-link {{ Request::is('bills.bulk-print-bill*') ? 'active' : '' }}">
                    <i class="fas fa-print nav-icon text-primary"></i><p>Bulk Printing</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('excemptions.index') }}"
                   class="nav-link {{ Request::is('excemptions.index*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Excemptions
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('katasNgVats.index') }}"
                   class="nav-link {{ Request::is('katasNgVats.index*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Katas Ng VAT
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.kwh-monitoring') }}"
                   class="nav-link {{ Request::is('bills.kwh-monitoring*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Kwh Monitoring</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.contestable-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.contestable-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Contestable Accounts</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.coop-consumption-accounts') }}"
                   class="nav-link {{ Request::is('serviceAccounts.coop-consumption-accounts') ? 'active' : '' }}"
                   title="Accounts with Termed Payments">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Coop Consumption Accts.</p>
                </a>
            </li>
            {{-- <li class="nav-header">                
                Others
            </li>
            <li class="nav-item">
                <a href="{{ route('pendingBillAdjustments.index') }}"
                   class="nav-link {{ Request::is('pendingBillAdjustments*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Zero Reading Adj.</p>
                </a>
            </li> --}}
        </ul>
    </li>
@endcanany
@canany(['Super Admin', 'billing re-bill'])
    {{-- METER READING --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-tachometer-alt nav-icon text-primary"></i>
            <p>
                Meter Reading
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('readingSchedules.reading-schedule-index') }}"
                   class="nav-link {{ Request::is('readingSchedules.reading-schedule-index*') ? 'active' : '' }}">                   
                   <i class="fas fa-calendar-week nav-icon text-primary"></i><p>M. Reader Scheduler</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.reading-account-grouper') }}"
                   class="nav-link {{ Request::is('serviceAccounts.reading-account-grouper*') ? 'active' : '' }}">                   
                   <i class="fas fa-calendar-alt nav-icon text-primary"></i><p>Reading Schedules</p>
                </a>
            </li>            
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.meter-readers') }}"
                   class="nav-link {{ Request::is('serviceAccounts.meter-readers*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Meter Readers</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.manual-reading') }}"
                   class="nav-link {{ Request::is('readings.manual-reading*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Manual Reading</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.reading-monitor') }}"
                   class="nav-link {{ Request::is('readings.reading-monitor*') ? 'active' : '' }}">                   
                   <i class="fas fa-street-view nav-icon text-primary"></i><p>Reading Monitoring</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.route-checker') }}"
                   class="nav-link {{ Request::is('serviceAccounts.route-checker*') ? 'active' : '' }}">                   
                   <i class="fas fa-check nav-icon text-primary"></i><p>Route Checker</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('meterReaderTrackNames.index') }}"
                   class="nav-link {{ Request::is('meterReaderTrackNames.index*') ? 'active' : '' }}">                   
                   <i class="fas fa-map-marked-alt nav-icon text-primary"></i><p>M. Reader Tracks</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('read-and-bill-notices.index') }}" class="nav-link {{ Request::is('read-and-bill-notices*') ? 'active' : '' }}">
                    <i class="fas fa-info-circle nav-icon text-primary"></i><p>RBS Notices</p>
                </a>
            </li>
        </ul>
    </li>
    
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-sun nav-icon text-primary"></i>
            <p>
                Net Metering
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.net-metering-dashboard') }}"
                class="nav-link {{ Request::is('serviceAccounts.net-metering-dashboard*') ? 'active' : '' }}">                   
                <i class="fas fa-circle nav-icon text-primary"></i><p>Dashboard
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.net-metering') }}"
                class="nav-link {{ Request::is('serviceAccounts.net-metering*') ? 'active' : '' }}">                   
                <i class="fas fa-circle nav-icon text-primary"></i><p>Net Metered Accts.
                </p>
                </a>
            </li>
        </ul>
    </li>

    {{-- APPROVALS AND OVERRIDING --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-check nav-icon text-primary"></i>
            <p>
                Approvals
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('bills.bill-arrears-unlocking') }}"
                   class="nav-link {{ Request::is('bills.bill-arrears-unlocking*') ? 'active' : '' }}">                   
                   <i class="fas fa-unlock nav-icon text-primary"></i><p>Waived Surcharges</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.bill-adjustments-approval') }}"
                   class="nav-link {{ Request::is('bills.bill-adjustments-approval*') ? 'active' : '' }}">                   
                   <i class="fas fa-pen nav-icon text-primary"></i><p>Bill Adjustments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.bills-cancellation-approval') }}"
                   class="nav-link {{ Request::is('bills.bills-cancellation-approval*') ? 'active' : '' }}">                   
                   <i class="fas fa-check-circle nav-icon text-primary"></i><p>Bill Cancellations</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- TOOLS --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-wrench nav-icon text-primary"></i>
            <p>
                Tools
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('readings.erroneous-readings') }}"
                   class="nav-link {{ Request::is('readings.erroneous-readings*') ? 'active' : '' }}">                   
                   <i class="fas fa-exclamation nav-icon text-primary"></i><p>Erroneous Reading</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('readings.abrupt-increase-decrease') }}"
                   class="nav-link {{ Request::is('readings.abrupt-increase-decrease*') ? 'active' : '' }}">                   
                   <i class="fas fa-arrow-up nav-icon text-primary"></i><p>Abrupt Inc/Dec</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- BILLING REPORTS --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file nav-icon text-primary"></i>
            <p>
                Reports
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-header">                
                Accounts
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.account-list') }}"
                   class="nav-link {{ Request::is('serviceAccounts.account-list*') ? 'active' : '' }}">                   
                   <i class="fas fa-user nav-icon text-primary"></i><p>Account List</p>
                </a>
            </li>
            <li class="nav-header">                
                Bills
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.adjustment-reports') }}"
                   class="nav-link {{ Request::is('bills.adjustment-reports*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Adjustments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.adjustment-reports-with-gl') }}"
                   class="nav-link {{ Request::is('bills.adjustment-reports-with-gl*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Adjustments w/ GL Code
                    </p>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('bills.detailed-adjustments') }}"
                   class="nav-link {{ Request::is('bills.detailed-adjustments*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Detailed Adjustments
                    </p>
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ route('bills.lifeliners-report') }}"
                   class="nav-link {{ Request::is('bills.lifeliners-report*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Lifeliners
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.senior-citizen-report') }}"
                   class="nav-link {{ Request::is('bills.senior-citizen-report*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Senior Citizen
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.government-tax-report') }}"
                   class="nav-link {{ Request::is('bills.government-tax-report*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Govt. Tax Report
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.outstanding-report') }}"
                   class="nav-link {{ Request::is('bills.outstanding-report*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Outstanding
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.disconnected-reports') }}"
                   class="nav-link {{ Request::is('bills.disconnected-reports*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Paid Reconnections
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.disco-pullout-appr') }}"
                   class="nav-link {{ Request::is('serviceAccounts.disco-pullout-appr*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Disco/Pullout/Appr.
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.all-billed') }}"
                   class="nav-link {{ Request::is('bills.all-billed*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>All Billed
                   </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.newly-energized') }}"
                   class="nav-link {{ Request::is('bills.newly-energized*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Newly Energized
                   </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('bills.cancelled-bills') }}"
                   class="nav-link {{ Request::is('bills.cancelled-bills*') ? 'active' : '' }}">                   
                   <i class="fas fa-info-circle nav-icon text-primary"></i><p>Cancelled Bills
                    <span class="right badge badge-danger">New</span>
                   </p>
                </a>
            </li>
            <li class="nav-header">                
                Meter Reading 
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.billed-and-unbilled-reports') }}"
                   class="nav-link {{ Request::is('readings.billed-and-unbilled-reports*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>Billed & Unbilled</p>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('readings.efficiency-report') }}"
                   class="nav-link {{ Request::is('readings.efficiency-report*') ? 'active' : '' }}">                   
                   <i class="fas fa-chart-pie nav-icon text-primary"></i><p>Efficiency Report</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.disco-per-mreader') }}"
                   class="nav-link {{ Request::is('readings.disco-per-mreader*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Disco per M. Reader</p>
                </a>
            </li> --}}
            {{-- <li class="nav-item">
                <a href="{{ route('readings.disco-per-bapa') }}"
                   class="nav-link {{ Request::is('readings.disco-per-bapa*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Disco per BAPA</p>
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ route('readings.uncollected-per-mreader') }}"
                   class="nav-link {{ Request::is('readings.uncollected-per-mreader*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Uncollected per M. Reader</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('readings.excemptions-per-mreader') }}"
                   class="nav-link {{ Request::is('readings.excemptions-per-mreader*') ? 'active' : '' }}">
                    <i class="fas fa-circle nav-icon text-primary"></i><p>Excemptions per M. Reader</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kwhSales.index') }}"
                   class="nav-link {{ Request::is('kwhSales.index*') ? 'active' : '' }}">                   
                   <i class="fas fa-plug nav-icon text-primary"></i><p>KWH Sales</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kwhSales.sales-distribution') }}"
                   class="nav-link {{ Request::is('kwhSales.sales-distribution*') ? 'active' : '' }}">                   
                   <i class="fas fa-file nav-icon text-primary"></i><p>Sales Distribution</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kwhSales.kwh-sales-expanded') }}"
                   class="nav-link {{ Request::is('kwhSales.kwh-sales-expanded*') ? 'active' : '' }}">                   
                   <i class="fas fa-circle nav-icon text-primary"></i><p>KWH Sales Expanded</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany

{{-- DISCONNECTION --}}
@canany(['Super Admin', 'billing re-bill', 'teller approve'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-users-slash nav-icon text-primary"></i>
            <p>
                Disconnection
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('discoNoticeHistories.index') }}"
                   class="nav-link {{ Request::is('discoNoticeHistories.index*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon text-primary"></i><p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('discoNoticeHistories.generate-nod') }}"
                   class="nav-link {{ Request::is('discoNoticeHistories.generate-nod*') ? 'active' : '' }}">
                    <i class="fas fa-file nav-icon text-primary"></i><p>Generate NoD</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('disconnectionHistories.generate-turn-off-list') }}"
                   class="nav-link {{ Request::is('disconnectionHistories.generate-turn-off-list*') ? 'active' : '' }}">
                    <i class="fas fa-ban nav-icon text-primary"></i><p>Turn-Off List 
                    </p>
                </a>
            </li>
        </ul>
    </li>
@endcanany

<!-- TELLERING MENU -->
@canany(['Super Admin', 'teller create'])
    <li class="nav-header">COLLECTION</li>
    <li class="nav-item">
        <a href="{{ route('dCRSummaryTransactions.dashboard') }}"
        class="nav-link {{ Request::is('dCRSummaryTransactions.dashboard*') ? 'active' : '' }}">
        <i class="fas fa-chart-line nav-icon text-info"></i><p>Dashboard</p>
        </a>
    </li>
    {{-- <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-credit-card nav-icon text-info"></i>
            <p>
                Collection
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('paidBills.index') }}"
                class="nav-link {{ Request::is('paidBills.index*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar nav-icon text-info"></i><p>Bills Payment</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paidBills.bapa-payments') }}"
                class="nav-link {{ Request::is('paidBills.bapa-payments*') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon text-info"></i><p>BAPA Payments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.service-connection-collection') }}"
                class="nav-link {{ Request::is('transactionIndices.service-connection-collection*') ? 'active' : '' }}">
                <i class="fas fa-plug nav-icon text-info"></i><p>Service Connection</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.uncollected-arrears') }}"
                class="nav-link {{ Request::is('transactionIndices.uncollected-arrears*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle nav-icon text-info"></i><p>Uncollected Arrears</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.reconnection-collection') }}"
                class="nav-link {{ Request::is('transactionIndices.reconnection-collection*') ? 'active' : '' }}">
                <i class="fas fa-link nav-icon text-info"></i><p>Reconnection</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transactionIndices.other-payments') }}"
                class="nav-link {{ Request::is('transactionIndices.other-payments*') ? 'active' : '' }}">
                <i class="fas fa-coins nav-icon text-info"></i><p>Other Payments</p>
                </a>
            </li>
        </ul>
    </li> --}}
@endcanany
@canany(['Super Admin', 'teller view'])
    <li class="nav-item">
        <a href="{{ route('transactionIndices.browse-ors') }}"
        class="nav-link {{ Request::is('transactionIndices.browse-ors*') ? 'active' : '' }}">
        <i class="fas fa-search nav-icon text-info"></i><p>Browse ORs</p>
        </a>
    </li>
@endcanany
{{-- @canany(['Super Admin', 'teller create'])
    <li class="nav-item">
        <a href="{{ route('transactionIndices.or-maintenance') }}"
        class="nav-link {{ Request::is('transactionIndices.or-maintenance*') ? 'active' : '' }}">
        <i class="fas fa-circle nav-icon text-info"></i><p>OR Maintenance</p>
        </a>
    </li>
@endcanany --}}
@canany(['Super Admin', 'teller create', 'teller approve'])
    <li class="nav-item">
        <a href="{{ route('paidBills.third-party-collection') }}"
        class="nav-link {{ Request::is('paidBills.third-party-collection*') ? 'active' : '' }}">
        <i class="fas fa-sign-out-alt nav-icon text-info"></i><p>Third-party Collection
        </p>
        </a>
    </li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-ban nav-icon text-info"></i>
            <p>
                OR Cancellations
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('paidBills.or-cancellation') }}"
                class="nav-link {{ Request::is('paidBills.or-cancellation*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar nav-icon text-info"></i><p>Bills Payment</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('oRCancellations.other-payments') }}"
                class="nav-link {{ Request::is('oRCancellations.other-payments*') ? 'active' : '' }}">
                <i class="fas fa-plug nav-icon text-info"></i><p>Other Payments</p>
                </a>
            </li>
        </ul>
    </li>
    {{-- TELLERS MODULE --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-users nav-icon text-info"></i>
            <p>
                Tellers
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('dCRSummaryTransactions.collection-summary-per-teller') }}"
                class="nav-link {{ Request::is('dCRSummaryTransactions.collection-summary-per-teller*') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon text-info"></i><p>Collection Summary</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('collection-date-adjustments.index') }}"
                class="nav-link {{ Request::is('collection-date-adjustments.index*') ? 'active' : '' }}">
                <i class="fas fa-calendar nav-icon text-info"></i><p>Collection Date Adj.</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany
@canany(['Super Admin', 'teller approve'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-check nav-icon text-info"></i>
            <p>
                Approvals
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('oRCancellations.index') }}"
                class="nav-link {{ Request::is('oRCancellations.index*') ? 'active' : '' }}">
                <i class="fas fa-ban nav-icon text-info"></i><p>OR Cancellations</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany
@canany(['Super Admin', 'teller create', 'teller approve'])
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-file nav-icon text-info"></i>
            <p>
                Reports
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('dCRSummaryTransactions.index') }}"
                class="nav-link {{ Request::is('dCRSummaryTransactions.index*') ? 'active' : '' }}">
                <i class="fas fa-file-alt nav-icon text-info"></i><p>DCR Summary</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dCRSummaryTransactions.application-dcr-summary') }}"
                class="nav-link {{ Request::is('dCRSummaryTransactions.application-dcr-summary*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon text-info"></i><p>Applications DCR Summary
                    <span class="right badge badge-danger">New</span>
                </p>
                </a>
            </li>
            @canany(['Super Admin', 'teller approve'])
            <li class="nav-item">
                <a href="{{ route('dCRSummaryTransactions.sales-dcr-monitor') }}"
                class="nav-link {{ Request::is('dCRSummaryTransactions.sales-dcr-monitor*') ? 'active' : '' }}">
                <i class="fas fa-chart-area nav-icon text-info"></i><p>Sales Monitor</p>
                </a>
            </li>
            @endcanany
            <li class="nav-item">
                <a href="{{ route('paidBills.collection-summary-report') }}"
                class="nav-link {{ Request::is('paidBills.collection-summary-report*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon text-info"></i><p>Collection Summary
                    <span class="right badge badge-danger">New</span></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paidBills.aging-report') }}"
                class="nav-link {{ Request::is('paidBills.aging-report*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon text-info"></i><p>Aging Outstanding
                    <span class="right badge badge-danger">New</span></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paidBills.third-party-report') }}"
                class="nav-link {{ Request::is('paidBills.third-party-report*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon text-info"></i><p>Third Party Payments
                    <span class="right badge badge-danger">New</span></p>
                </a>
            </li>
        </ul>
    </li>
@endcanany
{{-- @canany(['Super Admin', 'teller create'])
    <li class="nav-header">                
        Others
    </li>
    <li class="nav-item">
        <a href="{{ route('prePaymentBalances.index') }}"
           class="nav-link {{ Request::is('prePaymentBalances.index*') ? 'active' : '' }}">
           <i class="fas fa-piggy-bank nav-icon text-info"></i><p>Pre-Payments/Deposits</p>
        </a>
    </li>
@endcanany --}}
@canany(['Super Admin', 'teller create', 'teller approve'])
    <li class="nav-header">                
        Settings
    </li>
    <li class="nav-item">
        <a href="{{ route('accountPayables.index') }}"
           class="nav-link {{ Request::is('accountPayables*') ? 'active' : '' }}">
           <i class="fas fa-circle nav-icon text-info"></i><p>Account Payables</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('accountGLCodes.index') }}"
           class="nav-link {{ Request::is('accountGLCodes*') ? 'active' : '' }}">
           <i class="fas fa-circle nav-icon text-info"></i><p>GL Codes</p>
        </a>
    </li>  
@endcanany
{{-- BWUI --}}
@canany(['Super Admin', 'create membership', 'sc create', 'teller create', 'teller approve'])
    <li class="nav-header">BWUI</li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-hand-holding-water nav-icon"></i>
            <p>
                BWUI Module
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('towns.index') }}"
                class="nav-link {{ Request::is('towns*') ? 'active' : '' }}">
                <i class="fas fa-user nav-icon"></i><p>Consumer Accounts</p>
                </a>
            </li>   
            <li class="nav-item">
                <a href="{{ route('boholWaterServiceAccounts.upload-file') }}"
                class="nav-link {{ Request::is('boholWaterServiceAccounts.upload-file*') ? 'active' : '' }}">
                <i class="fas fa-upload nav-icon"></i><p>Upload New Data</p>
                </a>
            </li>           
        </ul>
    </li>
@endcanany
<!-- EXTRAS MENU -->
@canany(['Super Admin', 'create membership', 'sc create', 'teller create', 'teller approve'])
    <li class="nav-header">MISCELLANEOUS</li>
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-ellipsis-v nav-icon"></i>
            <p>
                Extras
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('towns.index') }}"
                class="nav-link {{ Request::is('towns*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt nav-icon"></i><p>Towns</p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('barangays.index') }}"
                class="nav-link {{ Request::is('barangays*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt nav-icon"></i><p>Barangays</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('zones.index') }}"
                   class="nav-link {{ Request::is('zones*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon"></i><p>Zones</p>
                </a>
            </li>
            
            
            <li class="nav-item">
                <a href="{{ route('blocks.index') }}"
                   class="nav-link {{ Request::is('blocks*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon"></i><p>Blocks</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('serviceConnectionCrews.index') }}"
                   class="nav-link {{ Request::is('serviceConnectionCrews*') ? 'active' : '' }}">
                   <i class="fas fa-map-marked-alt nav-icon"></i><p>Station Crews</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('rateItems.index') }}"
                class="nav-link {{ Request::is('rateItems*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon"></i><p>Rate Items</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('banks.index') }}"
                   class="nav-link {{ Request::is('banks*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon"></i><p>Banks</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('signatories.index') }}"
                   class="nav-link {{ Request::is('signatories*') ? 'active' : '' }}">
                   <i class="fas fa-circle nav-icon"></i><p>Signatories</p>
                </a>
            </li>
            
        </ul>
    </li>
    {{-- MIDDLEWARE --}}
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-code-branch nav-icon"></i>
            <p>
                Middleware
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.mw-customer-master-list') }}"
                class="nav-link {{ Request::is('serviceAccounts.mw-customer-master-list*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon"></i><p>Customer Master List</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.mw-customer-list') }}"
                class="nav-link {{ Request::is('serviceAccounts.mw-customer-list*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon"></i><p>Customer List w/ Demand</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.mw-advance-material-deposit-balances') }}"
                class="nav-link {{ Request::is('serviceAccounts.mw-advance-material-deposit-balances*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon"></i><p>Adv. Material Deposit Bal.</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('serviceAccounts.mw-customer-deposit-balances') }}"
                class="nav-link {{ Request::is('serviceAccounts.mw-customer-deposit-balances*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon"></i><p>Customer Deposit Bal.</p>
                </a>
            </li>

        </ul>
    </li>
@endcanany

<!-- ADMIN MENU -->
@can('Super Admin')
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
            <i class="fas fa-shield-alt nav-icon"></i>
            <p>
                Administrator
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('users.index') }}"
                class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                    <p><i class="fas fa-user-lock nav-icon"></i> Users</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('thirdPartyTokens.index') }}"
                   class="nav-link {{ Request::is('thirdPartyTokens*') ? 'active' : '' }}">
                    <p><i class="fas fa-code nav-icon"></i> Third Party Tokens</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('roles.index') }}"
                class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
                <i class="fas fa-unlock-alt nav-icon"></i><p>Roles</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('permissions.index') }}"
                class="nav-link {{ Request::is('permissions*') ? 'active' : '' }}">
                <i class="fas fa-key nav-icon"></i><p>Permissions</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('settings.settings') }}"
                class="nav-link {{ Request::is('settings.settings*') ? 'active' : '' }}">
                <i class="fas fa-cogs nav-icon"></i><p>General Settings</p>
                </a>
            </li>
        </ul>
    </li>
@endcan


