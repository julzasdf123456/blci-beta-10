<div>
   <span class="text-muted" style="margin-left: 3px;"><i class="fas fa-hard-hat ico-tab-mini"></i><strong>Service Connections</strong> Statistics</span>
   <div class="row">
       {{-- STATUS COUNT --}}
       <div class="col-lg-3">                
           <div class="small-box bg-info">
               <div class="inner">
                   <h3 id="new-applications">...</h3>
                   <p>New Applications</p>
               </div>
               <div class="icon">
                   <i class="fas fa-file"></i>
               </div>
               <a href="#" id="new-applications-btn" class="small-box-footer" title="New Applications for Inspection"  data-toggle="modal" data-target="#modal-stats">View <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>

       <div class="col-lg-3">                
           <div class="small-box bg-warning">
               <div class="inner">
                   <h3 id="for-meter-assigning">...</h3>
                   <p>For Meter Assigning</p>
               </div>
               <div class="icon">
                   <i class="fas fa-forward"></i>
               </div>
               <a href="{{ route('serviceConnectionMtrTrnsfrmrs.assigning') }}" class="small-box-footer" title="Tickets Sent To Crew" >View <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>

       <div class="col-lg-3">                
           <div class="small-box bg-success">
               <div class="inner">
                   <h3 id="for-energization">...</h3>
                   <p>For Energization</p>
               </div>
               <div class="icon">
                   <i class="fas fa-calendar-check"></i>
               </div>
               <a href="{{ route('serviceConnections.energization') }}" id="for-energization-btn" class="small-box-footer" title="Tickets Executed This Month">View <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>

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
                       <th>Verifier</th>
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
                           $('#approved-table tbody').append(`<tr><td><a href="{{ url('/serviceConnections')}}/` + response[index]["id"] + `">` + response[index]['id'] + `</a></td><td>` + response[index]['ServiceAccountName'] + `</td><td>` + response[index]['ConnectionApplicationType'] + `</td><td>` + response[index]['Barangay'] + `, ` + response[index]['Town'] + `</td><td>` + (jQuery.isEmptyObject(response[index]['name']) ? 'n/a' : response[index]['name']) + `</td></tr>`);
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
       })
   </script>
@endpush