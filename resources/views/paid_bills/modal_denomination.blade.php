<div class="modal fade" id="modal-denominate" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Denomination</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th class="text-right" style="width: 50%">One Thousands (1,000)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="thousand">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Five Hundreds (500)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="fivehundred">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">One Hundreds (100)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="onehundred">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Fiftys (50)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="fifty">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Twentys (20)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="twenty">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Tens (10)</th><td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="ten">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Fives (5)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="five">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Ones (1)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="one">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Centavos (.00)</th>
                        <td>
                            <input type="number" step="any" style="width: 30%" class="form-control" id="cents">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col text-center">
                        <button id="save-denomination" class="btn btn-primary"><i class="fas fa-check-circle ico-tab"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
