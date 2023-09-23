@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        {!! Form::open(['route' => 'transactionIndices.browse-ors', 'method' => 'GET']) !!}
            <div class="row mb-2">
                <div class="col-md-6 offset-md-3">
                    <input type="text" class="form-control" id="searchParam" placeholder="Search OR Numbers" name="params" value="{{ $params }}">
                </div>
                <div class="col-md-3">
                    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</section>

<div class="content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <table class="table table-sm table-hover">
                <thead>
                    <th>OR Number</th>
                    <th>OR Date</th>
                    <th>Account Number</th>
                    <th class="text-right">Amount Paid</th>
                    <th class="text-right">Payment Type</th>
                    <th width="60px"></th>
                </thead>
                <tbody>
                    @foreach ($allPayments as $item)
                        <tr>
                            <td>{{ $item->ORNumber }}</td>
                            <td>{{ $item->ORDate }}</td>
                            <td><a href="{{ $item->AccountNumber != null ? (route('serviceAccounts.show', [$item->AccountNumber])) : '' }}">{{ $item->AccountNumber }}</a></td>
                            <td class="text-right">{{ $item->Total != null ? number_format($item->Total, 2) : '-' }}</td>
                            <td class="text-right">{{ $item->PaymentType }}</td>
                            <td class="text-right">
                                <a href="{{ route('transactionIndices.browse-ors-view', [$item->id, $item->PaymentType]) }}" title="View this OR"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>    
</div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#searchParam').focus()
        })
    </script>
@endpush