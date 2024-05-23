<div class="row p-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link custom-tab active" href="#line" data-toggle="tab">
                        <i class="fas fa-bezier-curve"></i>
                        Line Services</a></li>
                    <li class="nav-item"><a class="nav-link custom-tab" href="#metering-svcs" data-toggle="tab">
                        <i class="fas fa-tachometer-alt"></i>
                        Metering Services</a></li>
                 <ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    {{-- MATERIALS --}}
                    <div class="tab-pane active" id="line">
                       @include('service_connections.tab_line_services')
                    </div>
     
                    {{-- METER --}}
                    <div class="tab-pane" id="metering-svcs">
                       @include('service_connections.tab_metering_services')
                    </div>
                </div>            
            </div>
            <div class="card-footer">
               
            </div>
        </div>
    </div>
</div>