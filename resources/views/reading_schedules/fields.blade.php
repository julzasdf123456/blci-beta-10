@php
    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }

    // GROUPS/DAY
    $groups = [
        '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'
    ];
@endphp

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Select Billing Month') !!}
    <select name="ServicePeriod" id="ServicePeriod" class="form-control">
        @for ($i = 0; $i < count($months); $i++)
            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
        @endfor
    </select>
</div>

<!-- Areacode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AreaCode', 'Area') !!}
    <select name="AreaCode" id="AreaCode" class="form-control">
        @foreach ($towns as $item)
            <option value="{{ $item->id }}">{{ $item->id . ' - ' . $item->Town }}</option>
        @endforeach
    </select>
</div>

<!-- Groupcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GroupCode', 'Day/Group') !!}
    <select name="GroupCode" id="GroupCode" class="form-control">
        @for ($i = 0; $i < count($groups); $i++)
            <option value="{{ $groups[$i] }}">{{ $groups[$i] }}</option>
        @endfor
    </select>
</div>



<!-- Scheduleddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ScheduledDate', 'Reading Date') !!}
    {!! Form::text('ScheduledDate', null, ['class' => 'form-control','id'=>'ScheduledDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ScheduledDate').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush
