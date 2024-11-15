<div>
   <span class="text-muted" style="margin-left: 3px;"><i class="fas fa-hard-hat ico-tab-mini"></i><strong>Service Connection Applications</strong></span>
   <div class="row mt-3">
        {{-- Requests for inspection --}}
        @canany(['Super Admin', 'sc view'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">Requests for Inspection</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="request-for-inspection">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="#" id="request-for-inspection-btn" class="btn btn-block btn-transparent" title="New Applications for Inspection"  data-toggle="modal" data-target="#modal-stats">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany

       {{-- new Applications --}}
       @canany(['Super Admin', 'sc view'])
       <div class="col-lg-3">                
           <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">New Applications</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="new-applications">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="#" id="new-applications-btn" class="btn btn-block btn-transparent" title="New Applications Received"  data-toggle="modal" data-target="#modal-stats">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
           </div>
       </div>
       @endcanany

       {{-- For Payment Approvals --}}
       @canany(['Super Admin', 'payment approval'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">For Payment Approvals</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="for-payment-approvals">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="{{ route('serviceConnections.payment-approvals') }}" class="btn btn-block btn-transparent" title="Approved applications for payment approval">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany

        {{-- For Payment Orders --}}
        @canany(['Super Admin', 'sc view'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">For Payments</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="for-payment-orders">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="{{ route('serviceConnections.for-payment') }}" class="btn btn-block btn-transparent" title="Approved applications for payment order creation">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany

        {{-- For Turn-on Approvals --}}
        @canany(['Super Admin', 'turn-on approval'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">For Turn-on Approvals</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="for-turn-on-approvals">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="{{ route('serviceConnections.turn-on-approvals') }}" class="btn btn-block btn-transparent" title="Paid applications for turn-on approvals">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany

        {{-- For Energization --}}
        @canany(['Super Admin', 'turn-on assigning'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">For Energization</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="for-energization">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="{{ route('serviceConnections.for-energization') }}" class="btn btn-block btn-transparent" title="Paid new applications for energization">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany
   </div>

   <span class="text-muted" style="margin-left: 3px;"><i class="fas fa-hard-hat ico-tab-mini"></i><strong>Other Applications</strong></span>
   <div class="row mt-3">
        {{-- Applied Requests --}}
        @canany(['Super Admin', 'turn-on assigning'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">Applied Requests</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="applied-requests">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="{{ route('serviceConnections.applied-requests') }}" class="btn btn-block btn-transparent" title="Applied requests">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany
        {{-- Other Services --}}
        @canany(['Super Admin', 'turn-on assigning'])
        <div class="col-lg-3">                
            <div class="card shadow-none">
                <div class="card-body p-0 mt-3 mb-2">
                    <div class="inner">
                        <p class="no-pads text-muted text-center">Other Services</p>
                        <h1 class="text-center strong text-xxl text-success mt-3" id="other-services">...</h1>
                    </div>
                    <div class="px-3">
                        <a href="{{ route('serviceConnections.other-services') }}" class="btn btn-block btn-transparent" title="Paid other services for execution">View <i class="fas fa-arrow-circle-right ico-tab-left-mini"></i></a>
                    </div>
                </div>               
            </div>
        </div>
        @endcanany
    </div>
</div>

{{-- MODAL FOR APPROVED AND FOR PAYMENT --}}
<div class="modal fade" id="modal-stats" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-xl">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title" id="modal-title">Approved Applicants</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
               <table class="table table-sm table-hover" id="approved-table">
                   <thead>
                       <th>ID</th>
                       <th>Service Account Name</th>
                       <th>Type</th>
                       <th>Address</th>
                       <th>Inspector</th>
                   </thead>
                   <tbody>

                   </tbody>
               </table>
           </div>
           <div class="modal-footer justify-content-between">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
       </div>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>

@push('page_scripts')
   <script>
       $(document).ready(function() {
           /**
            * FOR INSPECTION/ NEW APPLICATIONS
            */
           $.ajax({
               url : '{{ route("home.get-new-service-connections") }}',
               type: "GET",
               dataType : "json",
               success : function(response) {
                   console.log(response.length);
                   $('#new-applications').text(response.length);
               },
               error : function(error) {
                   $('#new-applications').text("Error!");
               }
           })

           $('#new-applications-btn').on('click', function() {
               $('#modal-title').text('New Applications')
               $.ajax({
                   url : '{{ route("home.get-new-service-connections") }}',
                   type: "GET",
                   dataType : "json",
                   success : function(response) {
                       $('#approved-table tbody tr').remove();
                       $.each(response, function(index, element) {
                           console.log(response[index]['id']);
                           $('#approved-table tbody').append(`<tr><td><a href="{{ url('/serviceConnections')}}/` + response[index]["id"] + `">` + response[index]['id'] + `</a></td><td>` + response[index]['ServiceAccountName'] + `</td><td>` + response[index]['AccountApplicationType'] + `</td><td>` + response[index]['Barangay'] + `, ` + response[index]['Town'] + `</td><td>` + (jQuery.isEmptyObject(response[index]['name']) ? 'n/a' : response[index]['name']) + `</td></tr>`);
                       });
                   },
                   error : function(error) {
                       // alert(error);
                       Toast.fire({
                           icon : 'error',
                           text : 'Error showing new applications'
                       })
                   }
               })
           })

           $('#request-for-inspection-btn').on('click', function() {
               $('#modal-title').text('Requests for Inspection')
               $.ajax({
                   url : '{{ route("home.get-requests-for-inspection") }}',
                   type: "GET",
                   dataType : "json",
                   success : function(response) {
                       $('#approved-table tbody tr').remove();
                       $.each(response, function(index, element) {
                           console.log(response[index]['id']);
                           $('#approved-table tbody').append(`<tr><td><a href="{{ url('/serviceConnections')}}/` + response[index]["id"] + `">` + response[index]['id'] + `</a></td><td>` + response[index]['ServiceAccountName'] + `</td><td>` + response[index]['AccountApplicationType'] + `</td><td>` + response[index]['Barangay'] + `, ` + response[index]['Town'] + `</td><td>` + (jQuery.isEmptyObject(response[index]['name']) ? 'n/a' : response[index]['name']) + `</td></tr>`);
                       });
                   },
                   error : function(error) {
                       // alert(error);
                       Toast.fire({
                           icon : 'error',
                           text : 'Error showing inspection requests'
                       })
                   }
               })
           })

           /**
            * INSPECTION REQUESTS
            */
            $.ajax({
               url : '{{ route("home.get-requests-for-inspection") }}',
               type: "GET",
               success : function(response) {
                   $('#request-for-inspection').text(response.length)
               },
               error : function(error) {
                    // $('#request-for-inspection').text('error')
                   console.log(error)
               }
           })

           /**
            * FOR PAYMENT APPROVALS
            */
            $.ajax({
               url : '{{ route("home.fetch-for-payment-approvals") }}',
               type: "GET",
               success : function(response) {
                   $('#for-payment-approvals').text(response.length)
               },
               error : function(error) {
                    $('#for-payment-approvals').text('error')
                   console.log(error)
               }
           })

           /**
            * FOR PAYMENT ORDERS
            */
            $.ajax({
               url : '{{ route("home.fetch-for-payment-orders") }}',
               type: "GET",
               success : function(response) {
                   $('#for-payment-orders').text(response.length)
               },
               error : function(error) {
                    $('#for-payment-orders').text('error')
                   console.log(error)
               }
           })

           /**
            * FOR TURN ON APPROVALS
            */
            $.ajax({
               url : '{{ route("home.fetch-for-turn-on-approvals") }}',
               type: "GET",
               success : function(response) {
                   $('#for-turn-on-approvals').text(response.length)
               },
               error : function(error) {
                    $('#for-turn-on-approvals').text('error')
                   console.log(error)
               }
           })

           /**
            * UNASSIGNED METERS 
            */
           $.ajax({
               url : '{{ route("home.get-unassigned-meters") }}',
               type: "GET",
               dataType : "json",
               success : function(response) {
                   console.log(response.length);
                   $('#for-meter-assigning').text(response.length)
               },
               error : function(error) {
                   
               }
           })


           /**
            * FOR ENERGIZATION 
            */
            $.ajax({
               url : '{{ route("home.get-for-engergization") }}',
               type: "GET",
               dataType : "json",
               success : function(response) {
                   console.log(response.length);
                   $('#for-energization').text(response.length);
               },
               error : function(error) {
                   // alert(error);
                   console.log('Server error!');
               }
           })

           /**
            * OTHER SERVICES
            */
            $.ajax({
               url : '{{ route("home.fetch-other-services") }}',
               type: "GET",
               dataType : "json",
               success : function(response) {
                   console.log(response.length);
                   $('#other-services').text(response.length);
               },
               error : function(error) {
                   // alert(error);
                   console.log('Server error!');
               }
           })

           /**
            * APPLIED REQUESTS
            */
            $.ajax({
               url : '{{ route("serviceConnections.get-applied-requests") }}',
               type: "GET",
               success : function(response) {
                   console.log(response.length);
                   $('#applied-requests').text(response.length);
               },
               error : function(error) {
                   // alert(error);
                   console.log('Server error!');
               }
           })
       })
   </script>
@endpush