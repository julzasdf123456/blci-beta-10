@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        {!! Form::open(['route' => 'bills.all-bills', 'method' => 'GET']) !!}
            <div class="row mb-2">
                <div class="col-md-6 offset-md-3">
                    <input type="text" class="form-control" placeholder="Search Bill No, Account No, Name" name="params" value="{{ old('params') }}">
                </div>
                <div class="col-md-3">
                    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</section>

<div class="content px-3">

    @include('flash::message')

    <div class="clearfix"></div>

    <table class="table table-hover">
        <thead>
            <th>Bill Number</th>
            <th>Account ID</th>
            <th>Account Number</th>
            <th>Service Account Name</th>
            <th>Address</th>
            <th>Billing Month</th>
            <th>Consumer Type</th>
            <th>OR Number</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($bills as $item)
                <tr>
                    <td>{{ $item->BillNumber }}</td>
                    <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->AccountNumber }}</a></td>
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }} {{ $item->AccountCount != null ? '(# ' . $item->AccountCount . ')' : '' }}</td>                
                    <td>{{ $item->Barangay }}, {{ $item->Town }}</td>
                    <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                    <td>{{ $item->ConsumerType }}</td>
                    <td>{{ $item->ORNumber }}</td>
                    <td width="120">
                        <div class='btn-group'>
                            <a href="{{ route('bills.show', [$item->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
    
    {{ $bills->links() }}
</div>
@endsection