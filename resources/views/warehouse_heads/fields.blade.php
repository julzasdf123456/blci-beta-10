<!-- Orderno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('orderno', 'Orderno:') !!}
    {!! Form::text('orderno', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45]) !!}
</div>

<!-- Ent No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ent_no', 'Ent No:') !!}
    {!! Form::number('ent_no', null, ['class' => 'form-control']) !!}
</div>

<!-- Misno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('misno', 'Misno:') !!}
    {!! Form::text('misno', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Tdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tdate', 'Tdate:') !!}
    {!! Form::text('tdate', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45]) !!}
</div>

<!-- Emp Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('emp_id', 'Emp Id:') !!}
    {!! Form::text('emp_id', null, ['class' => 'form-control','maxlength' => 25,'maxlength' => 25]) !!}
</div>

<!-- Ccode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ccode', 'Ccode:') !!}
    {!! Form::text('ccode', null, ['class' => 'form-control','maxlength' => 5,'maxlength' => 5]) !!}
</div>

<!-- Dept Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dept', 'Dept:') !!}
    {!! Form::text('dept', null, ['class' => 'form-control','maxlength' => 5,'maxlength' => 5]) !!}
</div>

<!-- Pcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pcode', 'Pcode:') !!}
    {!! Form::text('pcode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Reqby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reqby', 'Reqby:') !!}
    {!! Form::text('reqby', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45]) !!}
</div>

<!-- Invoice Field -->
<div class="form-group col-sm-6">
    {!! Form::label('invoice', 'Invoice:') !!}
    {!! Form::text('invoice', null, ['class' => 'form-control','maxlength' => 25,'maxlength' => 25]) !!}
</div>

<!-- Orno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('orno', 'Orno:') !!}
    {!! Form::text('orno', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45]) !!}
</div>

<!-- Purpose Field -->
<div class="form-group col-sm-6">
    {!! Form::label('purpose', 'Purpose:') !!}
    {!! Form::text('purpose', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Serv Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('serv_code', 'Serv Code:') !!}
    {!! Form::text('serv_code', null, ['class' => 'form-control','maxlength' => 12,'maxlength' => 12]) !!}
</div>

<!-- Account No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('account_no', 'Account No:') !!}
    {!! Form::text('account_no', null, ['class' => 'form-control','maxlength' => 25,'maxlength' => 25]) !!}
</div>

<!-- Cust Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cust_name', 'Cust Name:') !!}
    {!! Form::text('cust_name', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Tot Amt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tot_amt', 'Tot Amt:') !!}
    {!! Form::number('tot_amt', null, ['class' => 'form-control']) !!}
</div>

<!-- Chkby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('chkby', 'Chkby:') !!}
    {!! Form::text('chkby', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Appby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appby', 'Appby:') !!}
    {!! Form::text('appby', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Stat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('stat', 'Stat:') !!}
    {!! Form::text('stat', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Rdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rdate', 'Rdate:') !!}
    {!! Form::text('rdate', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45]) !!}
</div>

<!-- Rtime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rtime', 'Rtime:') !!}
    {!! Form::text('rtime', null, ['class' => 'form-control','maxlength' => 25,'maxlength' => 25]) !!}
</div>

<!-- Walk In Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('walk_in', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('walk_in', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('walk_in', 'Walk In', ['class' => 'form-check-label']) !!}
    </div>
</div>


<!-- Appl No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appl_no', 'Appl No:') !!}
    {!! Form::text('appl_no', null, ['class' => 'form-control','maxlength' => 35,'maxlength' => 35]) !!}
</div>