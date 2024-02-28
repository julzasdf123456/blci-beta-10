{{-- MODAL FOR ADDING MATERIALS --}}
<div class="modal fade" id="modal-add-items" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-xl">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title">Add Material Item</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="form-group">
                  <label for="Search" style="display: inline;">Search</label>
                  <input type="text" id="Search" class="form-control form-control-sm" style="width: 350px; display: inline;" autofocus>
               </div>

               {{-- RESULTS --}}
               <table id="item-results" class="table table-sm table-bordered table-hover">
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
      $('#Search').keyup(function() {
         var len = this.value.length
         if (len > 3) {
            searchItems(this.value)
         } else {
            $('#item-results tbody tr').remove()
         }
      })

      $('#modal-add-items').on('shown.bs.modal', function() {
         $(this).find('[autofocus]').focus();
      });

      function searchItems(regex) {
         $('#item-results tbody tr').remove()
         $.ajax({
            url : "{{ route('warehouseItems.get-searched-materials') }}",
            type : 'GET',
            data : {
               Regex : regex,
            },
            success : function(res) {
               // $('#item-results tbody').append(res)
               populateResults(res)
            },
            error : function(err) {
               Toast.fire({
                  icon : 'error',
                  text : 'error fetching search results!'
               })
            }
         })
      }

      function populateResults(res) {
         $.each(res, function(index, element) {
            $('#item-results tbody').append(`
               <tr onclick=selectMaterialItem('` + res[index]['itmno'] + `')
                        id='` + res[index]['itmno'] + `' 
                        data_itcode='` + res[index]['itcode'] + `'
                        data_itdesc='` + res[index]['itdesc'] + `'
                        data_uom='` + res[index]['uom'] + `'
                        data_cst='` + res[index]['sprice'] + `' 
                        data_unitprice='` + res[index]['cst'] + `'>
                     <td>` + res[index]['itcode'] + `</td>
                     <td>` + res[index]['itdesc'] + `</td>
                     <td>` + res[index]['uom'] + `</td>
                     <td class='text-right'>` + (res[index]['cst']) + `</td>
                     <td class='text-right'>` + (res[index]['sprice']) + `</td>
                     <td class='text-right'>` + (res[index]['dprice']) + `</td>
                     <td class='text-right'>` + res[index]['qty'] + `</td>
               </tr>
            `)
         })
      }

      function selectMaterialItem(id) {
         $('#ItemCode').val($('#' + id).attr('data_itcode'))
         $('#ItemDescription').val($('#' + id).attr('data_itdesc'))

         selectedItemCost = parseFloat($('#' + id).attr('data_cst'))
         selectedUOM = $('#' + id).attr('data_uom')
         selectedUnitPrice = parseFloat($('#' + id).attr('data_unitprice'))

         $('#modal-add-items').modal('hide')
         $('#Search').val('')
         $('#item-results tbody tr').remove()
         $('#ItemQuantity').focus()
      }
   </script>
@endpush