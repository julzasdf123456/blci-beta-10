@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Captured Readings</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        {{-- PARAMS --}}
        <div class="row">
             {{-- PARAMS --}}
             <div class="col-lg-12 px-5">
                {{-- <form class="row" action="{{ route("bills.unbilled-readings-console", ['servicePeriod' => $servicePeriod]) }}" method="get"> --}}
                    
                <form class="row" action="" method="get">
                    <div class="form-group col-lg-2">
                        <label for="ServicePeriod">Billing Month</label>
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                    </div>
                    {{-- <div class="form-group col-lg-2">
                        <label for="Area">Area</label>
                        <input type="text" name="Area" value="{{ env('APP_AREA_CODE') }}" class="form-control form-control-sm">
                    </div>
                    <div class="form-group col-lg-2">
                        <label for="Group">Group/Day</label>
                        <select name="GroupCode" class="form-control form-control-sm">
                            <option value="01" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='01' ? 'selected' : '') : '' }}>01</option>
                            <option value="02" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='02' ? 'selected' : '') : '' }}>02</option>
                            <option value="03" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='03' ? 'selected' : '') : '' }}>03</option>
                            <option value="04" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='04' ? 'selected' : '') : '' }}>04</option>
                            <option value="05" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='05' ? 'selected' : '') : '' }}>05</option>
                            <option value="06" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='06' ? 'selected' : '') : '' }}>06</option>
                            <option value="07" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='07' ? 'selected' : '') : '' }}>07</option>
                            <option value="08" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='08' ? 'selected' : '') : '' }}>08</option>
                            <option value="09" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='09' ? 'selected' : '') : '' }}>09</option>
                            <option value="10" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='10' ? 'selected' : '') : '' }}>10</option>
                            <option value="11" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='11' ? 'selected' : '') : '' }}>11</option>
                            <option value="12" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='12' ? 'selected' : '') : '' }}>12</option>
                            <option value="13" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='13' ? 'selected' : '') : '' }}>13</option>
                        </select>
                    </div> --}}
                    <div class="form-group col-lg-2">
                        <label for="MeterReader">Meter Reader</label>
                        <select class="custom-select select2"  name="MeterReader">
                            @foreach ($meterReaders as $items)
                                <option value="{{ $items->id }}" {{ isset($_GET['MeterReader']) ? ($_GET['MeterReader']==$items->id ? 'selected' : '') : '' }}>{{ $items->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="divider"></div>

        <div class="row">
            {{-- LIST OF CAPTURED --}}
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>Reading</th>
                                <th>Read Date</th>
                                <th>Remarks</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @if ($readings != null)
                                    @foreach ($readings as $item)
                                        <tr id="{{ $item->id }}">
                                            <th>{{ $item->KwhUsed }}</th>
                                            <td>{{ date('M d, Y', strtotime($item->ReadingTimestamp)) }}</td>
                                            <td>{{ $item->Notes }}</td>
                                            <td class="text-right">
                                                <button onclick="markAsDone('{{ $item->id }}')" class="btn btn-xs btn-danger" title="Mark as done/erroneous"><i class="fas fa-times-circle"></i></button>
                                                <button onclick="fetchDetails('{{ $item->id }}', '{{ $item->KwhUsed }}')" class="btn btn-xs btn-success" title="Mark as done/erroneous"><i class="fas fa-forward"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PERFORM CAPTURE --}}
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-default" id="card-form">
                        <span class="card-title">
                            Correct Reading
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <span><i>Search Acount by Acct. No</i></span>
                                <input disabled class="form-control form-control-sm" id="old-account-no" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.3em; color: #b91400; font-weight: bold;">
                            </div>

                            <div class="col-lg-6">
                                <span><i>Search Acount by Name</i></span>
                                <input disabled class="form-control form-control-sm" id="name" autocomplete="off" style="font-weight: bold;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var readId = null

        $(document).ready(function() {
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

            $("#old-account-no").off('keyup').on('keyup', function(event) {
                if (this.value.length == 12) {
                    fetchAccount(this.value)
                }
            })
        })

        function enablers(bool) {
            if (bool) {
                $('#card-form').removeClass('bg-default').addClass('bg-primary')
                $('#old-account-no').removeAttr('disabled')
                $('#name').removeAttr('disabled')
            } else {
                $('#card-form').removeClass('bg-primary').addClass('bg-default')
                $('#old-account-no').attr('disabled', 'disabled')
                $('#name').attr('disabled', 'disabled')
            }
        }

        function markAsDone(id) {
            readId = null

            enablers(false)

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure you want to mark this reading as done and erroneous?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('readings.mark-as-done') }}",
                        type : 'GET',
                        data : {
                            id : id
                        },
                        success : function(res) {
                            $('#' + res['id']).remove()
                            Toast.fire({
                                icon: 'success',
                                title: 'Reading marked as done'
                            })
                        },
                        error : function(err) {
                            Swal.fire({
                                title : 'Oops',
                                text : 'An error occurred while performing this action',
                                icon : 'error'
                            })
                        }
                    })
                }
            })            
        }

        function fetchDetails(id, kwhused) {
            $('#' + readId).removeClass('bg-info')

            readId = id

            enablers(true)
            $('#' + id).addClass('bg-info')
            //basic search
            $('#old-account-no').focus()
        }

        function fetchAccount(accountNumber) {
            
        }
    </script>
@endpush