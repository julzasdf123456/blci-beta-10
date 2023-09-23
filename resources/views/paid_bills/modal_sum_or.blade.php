{{-- MODAL FOR SEARCHING OF CONSUMERS --}}
<div class="modal fade" id="modal-sum-or" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sum OR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th>From</th>
                        <td>
                            <input type="text" class="form-control" id="from-or-sum" style="font-size: 1.5em;">
                        </td>
                        <th>Number of Payments</th>
                        <td>
                            <input type="text" class="form-control" id="no-of-payments-sum" style="font-size: 1.5em;" readonly='true'>
                        </td>
                        <th>Enter Amount Paid</th>
                        <td>
                            <input type="number" step="any" class="form-control" id="amount-paid-sum" style="font-size: 1.5em;">
                        </td>
                    </tr>
                    <tr>
                        <th>To</th>
                        <td>
                            <input type="text" class="form-control" id="to-or-sum" style="font-size: 1.5em;">
                        </td>
                        <th>Total Amount</th>
                        <td>
                            <input type="text" class="form-control" id="total-amount-sum" style="font-size: 1.5em;" readonly='true'>
                        </td>
                        <th>Change</th>
                        <td>
                            <input type="text" class="form-control" id="change-sum" style="font-size: 1.5em;" readonly='true'>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col text-center">
                        <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-check-circle ico-tab"></i>Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        var total = 0.0
        var change = 0.0

        // $('#from-or-sum').on('keyup', function() {
        //     if (this.value.length > 4) {
        //         if (!jQuery.isEmptyObject(this.value) && !jQuery.isEmptyObject($('#to-or-sum').val())) {
        //             getORs()
        //         }                
        //     }     
        // })

        // $('#to-or-sum').keyup(function() {
        //     if (this.value.length > 4) {
        //         if (!jQuery.isEmptyObject(this.value) && !jQuery.isEmptyObject($('#from-or-sum').val())) {
        //             getORs()
        //         } 
        //     }
        // })

        // MODAL ON SHOW
        $("#modal-sum-or" ).on('shown.bs.modal', function(){      
            total = 0.0
            change = 0.0
            updateUI(total, '')
            $('#from-or-sum').val('').focus()
            $('#to-or-sum').val('')
            $('#change-sum').val(change)
            $('#amount-paid-sum').val('')

            $('#to-or-sum').val(parseInt($('#orNumber').val()) - 1)
        });

        // AMOUNT SUM ON TYPE
        $('#amount-paid-sum').keyup(function() {
            updateAmount()
        })

        function getORs() {
            var from = $('#from-or-sum').val()
            var to = $('#to-or-sum').val()

            total = 0.0
            change = 0.0

            updateUI(total, 0)
            updateAmount()

            $.ajax({
                url : "{{ route('paidBills.get-ors-from-range') }}",
                type : 'GET',
                data : {
                    From : from,
                    To : to,
                },
                success : function(res) {
                    // GET TOTAL
                    $.each(res, function(index, element) {
                        total = total + parseFloat(res[index]['NetAmount'])
                    })

                    // UPDATE UI
                    updateUI(total, res.length)
                    updateAmount()                  
                },
                error : function(error) {
                    Swal.fire({
                        title : 'Oops!',
                        text : 'An error occurred while trying to fetch the ORs',
                        icon : 'error'
                    })
                }
            })
        }

        function updateUI(total, noOfPayments) {
            $('#total-amount-sum').val(parseFloat(total).toFixed(2))
            $('#no-of-payments-sum').val(noOfPayments)
        }

        function updateAmount() {
            if (!jQuery.isEmptyObject($('#amount-paid-sum').val())) {
                var amnt = parseFloat($('#amount-paid-sum').val())

                change = amnt - total

                $('#change-sum').val(parseFloat(change).toFixed(2))
            }            
        }

        // DETECT ENTER
        $($('#from-or-sum')).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                getORs()
                $('#to-or-sum').focus() 
            }
        })

        $($('#to-or-sum')).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                getORs()
                $('#amount-paid-sum').focus() 
            }
        })

        $($('#amount-paid-sum')).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                $('#modal-sum-or').modal('hide') 
            }
        })
    </script>
@endpush