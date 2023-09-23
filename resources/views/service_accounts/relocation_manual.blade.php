@php
    use App\Models\ServiceAccounts;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <p class="text-center"><i class="fas fa-map-marked-alt ico-tab"></i>Relocation Search</p>
        <div class="container-fluid">
            {!! Form::open(['route' => 'serviceAccounts.relocation-manual', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-2 offset-md-1">
                        <input class="form-control" id="old-account-no" name="oldaccount" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.5em; color: #b91400; font-weight: bold;">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Search" name="params" value="{{ old('params') }}">
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

        <table class="table table-hover table-sm">
            <thead>
                <th>Account ID</th>
                <th>Legacy Account No.</th>
                <th>Service Account Name</th>
                <th>Meter Number</th>
                <th>Address</th>
                <th>Status</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($serviceAccounts as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->OldAccountNo != null ? $item->OldAccountNo : '-' }}</td>
                        <td>{{ $item->ServiceAccountName }} {{ $item->AccountCount != null ? '(# ' . $item->AccountCount . ')' : '' }}</td>      
                        <td>{{ $item->MeterNumber }}</td>          
                        <td>{{ ServiceAccounts::getAddress($item) }}</td>
                        <td>{{ $item->AccountStatus }}</td>
                        <td width="120">
                            <div class='btn-group'>
                                <a href="{{ route('serviceAccounts.relocation-form-manual', [$item->id]) }}"
                                   class='btn btn-primary btn-xs'>
                                    <i class="fas fa-map-marked-alt ico-tab-mini"></i>Relocate
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        
        {{ $serviceAccounts->links() }}
    </div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#old-account-no').focus()

            $("#old-account-no").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });
        })
    </script>
@endpush

