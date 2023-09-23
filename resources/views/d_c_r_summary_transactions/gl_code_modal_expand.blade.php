<div class="modal fade" id="modal-gl-code" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="gl-title">Transactions Paid for </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-gl" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm" id="gl-code-table">
                    <thead>
                        <th style="width: 35px;">#</th>
                        <th>Account No</th>
                        <th>Account Name</th>
                        <th>Amount</th>
                        <th>OR Number</th>
                        <th>Date Paid</th>
                        <th>Teller</th>
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