{{-- MODAL FOR SEARCHING OF CONSUMERS --}}
<div class="modal fade" id="modal-confirm-payment" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 40%;" class="text-right">Cash Amount</th>
                        <td style="width: 60%;">
                            <input type="number" step="any" class="form-control" id="cash-modal-confirm" style="width: 300px; font-size: 1.5em;">
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 40%;" class="text-right">Total Check Amount</th>
                        <td style="width: 60%;">
                            <input type="number" step="any" class="form-control" id="check-modal-confirm" style="width: 300px; font-size: 1.5em;" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 40%;" class="text-right">Total Amount Paid</th>
                        <td style="width: 60%;">
                            <input type="number" step="any" class="form-control" id="total-modal-confirm" style="width: 300px; font-size: 1.5em;" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 40%;" class="text-right">Amount Due</th>
                        <td style="width: 60%;">
                            <input type="number" step="any" class="form-control" id="amntdue-modal-confirm" style="width: 300px; color: red; font-size: 1.5em;" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 40%;" class="text-right">Change</th>
                        <td style="width: 60%;">
                            <input type="number" step="any" class="form-control" id="change-modal-confirm" style="width: 300px; font-size: 1.5em;" readonly="true">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col text-center">
                        <button id="confirm-modal-btn" class="btn btn-primary btn-lg"><i class="fas fa-check-circle ico-tab"></i>Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>