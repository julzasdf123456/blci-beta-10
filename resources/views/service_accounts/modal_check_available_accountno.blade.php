<div class="modal fade" id="modal-check-available-acctno" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Available Account Nos. for this Area</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control form-control-sm" id="check-acct-no-route">
                <table class="table table-hover table-sm" id="check-acct-no-table">
                    <thead>
                        <th>Account Numbers Available</th>
                        <th></th>
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
        $('#modal-check-available-acctno').on('shown.bs.modal', function () {
            $('#check-acct-no-table tbody tr').remove()
            $.ajax({
                url : "{{ route('serviceAccounts.check-available-account-numbers') }}",
                type : 'GET',
                data : {
                    AccountNumberSample : $('#check-acct-no-route').val(),
                },
                success : function(res) {
                    $('#check-acct-no-table tbody').append(res)
                },
                error : function(res) {
                    Swal.fire({
                        title : 'Error getting accounts',
                        icon : 'error'
                    })
                }
            })
        });

        function selectAccount(acct) {
            $('#OldAccountNo').val(acct)
            $('#OldAccountNo').focus()
            $('#modal-check-available-acctno').modal('hide')
            $('#check-acct-no-table tbody tr').remove()
        }
    </script>
@endpush