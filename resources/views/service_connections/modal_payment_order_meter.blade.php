{{-- MODAL FOR ADDING MATERIALS --}}
<div class="modal fade" id="modal-add-meter" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-xl">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title">Add Meter</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="form-group">
                  <label for="meter-Search" style="display: inline;">Search</label>
                  <input type="text" id="meter-Search" class="form-control form-control-sm" style="width: 350px; display: inline;" autofocus>
               </div>

               {{-- RESULTS --}}
               <table id="meter-item-results" class="table table-sm table-bordered table-hover">
                  <thead>
                     <th>Item Code</th>
                     <th>Name</th>
                     <th>UOM</th>
                     <th>Unit Price</th>
                     <th>Sales Price</th>
                     <th>Mat. Deposit<br>Price</th>
                     <th>Qty. In Stock</th>
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
      $('#meter-Search').keyup(function() {
         var len = this.value.length
         if (len > 3) {
            search(this.value)
         } else {
            $('#meter-item-results tbody tr').remove()
         }
      })

      $('#modal-add-meter').on('shown.bs.modal', function() {
         $(this).find('[autofocus]').focus();
      });

      function search(regex) {
         $('#meter-item-results tbody tr').remove()
         $.ajax({
            url : "{{ route('warehouseItems.get-searched-meters') }}",
            type : 'GET',
            data : {
               Regex : regex,
            },
            success : function(res) {
               $('#meter-item-results tbody').append(res)
            },
            error : function(err) {
               Toast.fire({
                  icon : 'error',
                  text : 'error fetching search results!'
               })
            }
         })
      }

      function selectMaterial(id) {
         $('#meter-ItemCode').val($('#' + id).attr('meter_data_itcode'))
         $('#meter-ItemDescription').val($('#' + id).attr('meter_data_itdesc'))

         selectedItemCost = parseFloat($('#' + id).attr('meter_data_cst'))
         selectedUOM = $('#' + id).attr('meter_data_uom')

         $('#modal-add-meter').modal('hide')
         $('#meter-Search').val('')
         $('#meter-item-results tbody tr').remove()
         $('#meter-ItemQuantity').focus()
      }
   </script>
@endpush