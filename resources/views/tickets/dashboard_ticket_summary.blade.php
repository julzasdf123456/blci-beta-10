<div>
   <span class="text-muted" style="margin-left: 3px;"><i class="fas fa-tools ico-tab-mini"></i><strong>Ticket</strong> Statistics</span>
   <div class="row">
       {{-- STATUS COUNT --}}
       <div class="col-lg-3">                
           <div class="small-box bg-info">
               <div class="inner">
                   <h3 id="new-tickets">...</h3>
                   <p>New Received Tickets</p>
               </div>
               <div class="icon">
                   <i class="fas fa-file"></i>
               </div>
               <a href="#" id="new-tickets-btn" class="small-box-footer" title="New Received Tickets"  data-toggle="modal" data-target="#modal-statistics">View <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>

       <div class="col-lg-3">                
           <div class="small-box bg-warning">
               <div class="inner">
                   <h3 id="sent-to-lineman">...</h3>
                   <p>Tickets Sent To Crew</p>
               </div>
               <div class="icon">
                   <i class="fas fa-forward"></i>
               </div>
               <a href="#" id="sent-to-lineman-btn" class="small-box-footer" title="Tickets Sent To Crew"  data-toggle="modal" data-target="#modal-statistics">View <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>

       <div class="col-lg-3">                
           <div class="small-box bg-success">
               <div class="inner">
                   <h3 id="executed-this-month">...</h3>
                   <p>Tickets Executed This Month</p>
               </div>
               <div class="icon">
                   <i class="fas fa-calendar-check"></i>
               </div>
               <a href="#" id="executed-this-month-btn" class="small-box-footer" title="Tickets Executed This Month"  data-toggle="modal" data-target="#modal-statistics">View <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>

       <div class="col-lg-3">                
           <div class="small-box bg-danger">
               <div class="inner">
                   <h3 id="average-execution-time">...</h3>
                   <p>Avg. Exec. Time This Month</p>
               </div>
               <div class="icon">
                   <i class="fas fa-tools"></i>
               </div>
               <a href="{{ route('tickets.kps-monitor') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
       </div>
   </div>
</div>

{{-- MODAL FOR SHOWING STAT DETAILS --}}
<div class="modal fade" id="modal-statistics" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-xl">
       <div class="modal-content">
           <div class="modal-header">
               <h4 id="stat-show-title" class="modal-title">...</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body table-responsive" style="height: 75vh;">
               <table class="table table-sm table-hover" id="results-table">
                   <thead>
                       <th>Account No.</th>
                       <th>Account Name</th>
                       <th>Address</th>
                       <th>Ticket/Complain</th>
                       <th id="date-performed">Date Filed</th>
                       <td width="30px;"></td>
                   </thead>
                   <tbody>

                   </tbody>
               </table>
           </div>
           <div class="modal-footer justify-content-between">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
       </div>
   </div>
</div>

@push('page_scripts')
   <script>
       $(document).ready(function() {
           fetchStatistics()

           $('#new-tickets-btn').on('click', function(e) {
               e.preventDefault()
               fetchStatDetails('Received')
               $('#stat-show-title').text($('#new-tickets-btn').attr('title'))
               $('#date-performed').text('Date Filed')
           })

           $('#sent-to-lineman-btn').on('click', function(e) {
               e.preventDefault()
               fetchStatDetails('Forwarded To Lineman')
               $('#stat-show-title').text($('#sent-to-lineman-btn').attr('title'))
               $('#date-performed').text('Date Forwarded')
           })

           $('#executed-this-month-btn').on('click', function(e) {
               e.preventDefault()
               fetchStatDetails('Executed')
               $('#stat-show-title').text($('#executed-this-month-btn').attr('title'))
               $('#date-performed').text('Date Executed')
           })
           /**
            * Get ticket statistics
            */
           function fetchStatistics() {
               $.ajax({
                   url : '/tickets/get-ticket-statistics',
                   type : 'GET',
                   success : function(res) {
                       if (!jQuery.isEmptyObject(res[0])) {
                           $('#new-tickets').text(res[0]['Received'])
                           $('#sent-to-lineman').text(res[0]['SentToLineman'])
                           $('#executed-this-month').text(res[0]['ExecutedThisMonth'])
                           $('#average-execution-time').text(res[0]['AverageExecutionTime'] + " hrs")
                       }
                   },
                   error : function(err) {
                       console.log(err)
                   }
               })
           }

           /**
            * FETCH Statistics details
            */
           function fetchStatDetails(query) {
               $('#results-table tbody tr').remove()
               $.ajax({
                   url : '/tickets/get-ticket-statistics-details',
                   type : 'GET',
                   data : {
                       Query : query
                   },
                   success : function(res) {
                       $('#results-table tbody').append(res)
                   },
                   error : function(err) {
                       console.log(err)
                   }
               })
           }
       })
   </script>
@endpush