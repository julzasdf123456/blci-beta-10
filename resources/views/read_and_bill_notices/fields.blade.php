@php
    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }

@endphp

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notice/Remarks:') !!}
    <textarea name="Notes" id="Notes" rows="3" class="form-control"></textarea>
</div>

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Billing Month to Show:') !!}
    <select name="ServicePeriod" id="ServicePeriod" class="form-control">
        @for ($i = 0; $i < count($months); $i++)
            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
        @endfor
    </select>
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriod').datepicker()
    </script>
@endpush

<!-- Userid Field -->
<input type="hidden" name="UserId" value="{{ Auth::id() }}">

<!-- Zone Field -->
<div class="form-group col-sm-3">
    {!! Form::label('Zone', 'Zone:') !!}
    {!! Form::text('Zone', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Block Field -->
<div class="form-group col-sm-3">
    {!! Form::label('Block', 'Block:') !!}
    {!! Form::text('Block', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>