    {{-- ALLOW SKIP --}}
    <div class="modal fade" id="modal-allow-skip" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Skip Bills Payment?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Allow this Bill to be skipped from the Cashiering app? </p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button onclick="allowSkip(`SKIP_AUTO`)" class="btn btn-success float-right">Skip Automatically</button>
                    <button onclick="allowSkip(`SKIP_MANUAL`)" class="btn btn-primary float-right">Allow Cashier to Manually Skip</button>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
        <script>
            function allowSkip(skipStatus) {
                $.ajax({
                    url : "{{ route('bills.allow-skip') }}",
                    type : 'GET',
                    data : {
                        id : "{{ $bills->id }}",
                        SkipStatus : skipStatus,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Billed allowed to be skipped'
                        })
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'Error skipping bill to cashier'
                        })
                    }
                })
            }
        </script>
    @endpush