{{-- MODAL FOR ADDING MATERIALS --}}
<div class="modal fade" id="modal-select-customers" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-xl">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title">Select and Search Customer</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="form-group">
                  <label for="Search" style="display: inline;">Search</label>
                  <input type="text" id="Search" class="form-control form-control-sm" placeholder="Customer Name, Account Number, Meter Number" style="width: 400px; display: inline;" autofocus>
               </div>

               {{-- RESULTS --}}
               <table id="item-results" class="table table-sm table-bordered table-hover">
                  <thead>
                     <th>Account No.</th>
                     <th>Account Name</th>
                     <th>Address</th>
                     <th>Meter Number</th>
                     <th>Account Type</th>
                  </thead>
                  <tbody>

                  </tbody>
               </table>
           </div>
       </div>
   </div>
</div>

@push('page_scripts')
   <script>
      $('#Search').keyup(function() {
         var len = this.value.length
         if (len > 4) {
            search(this.value)
         } else {
            $('#item-results tbody tr').remove()
         }
      })

      $('.modal').on('shown.bs.modal', function() {
         $(this).find('[autofocus]').focus();
      });

      function search(regex) {
         $('#item-results tbody tr').remove()
         $.ajax({
            url : "{{ route('serviceConnections.get-existing-accounts') }}",
            type : 'GET',
            data : {
               Params : regex,
            },
            success : function(res) {
               $('#item-results tbody').append(res)
            },
            error : function(err) {
               Toast.fire({
                  icon : 'error',
                  text : 'error fetching search results!'
               })
            }
         })
      }

      function selectCustomer(id) {
         $('#AccountNumber').val($('#' + id).attr('data_id'))
         $('#ServiceAccountName').val($('#' + id).attr('data_name'))
         $('#Barangay').val($('#' + id).attr('data_barangay'))
         $('#Sitio').val($('#' + id).attr('data_purok'))
         $('#ContactNumber').val($('#' + id).attr('data_contact'))

         $('#modal-select-customers').modal('hide')
         $('#Search').val('')
         $('#item-results tbody tr').remove()
      }
   </script>
@endpush