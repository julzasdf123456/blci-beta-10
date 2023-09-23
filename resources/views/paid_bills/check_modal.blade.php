@php
    use App\Models\Banks;

    $banks = Banks::all();
@endphp

{{-- MODAL FOR CHECK PAYMENT --}}
<div class="modal fade" id="modal-check-payment" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Provide Check Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- SEARCH --}}
                <div class="row">                    
                    <div class="form-group col-lg-12">
                        <input type="text" id="checkNo" placeholder="Check Number" class="form-control" autofocus="true">
                    </div>

                    <div class="form-group col-lg-12">
                        <input type="number" step="any" id="checkAmount" placeholder="Check Amount" class="form-control">
                    </div>

                    <div class="form-group col-lg-12">
                        <!-- <select name="bank" id="bank" class="form-control">
                            @foreach ($banks as $item)
                                <option value="{{ $item->BankAbbrev }}">{{ $item->BankFullName }} ({{ $item->BankAbbrev }})</option>
                            @endforeach
                        </select> -->
                        <input type="text" id="bank" placeholder="Bank" name="bank" class="form-control">
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button id="save-check-transaction" class="btn btn-primary"><i class="fas fa-check ico-tab-mini"></i> Add Check</button>
            </div>
        </div>
    </div>
</div>