@php
    use App\Models\ServiceAccounts;

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
                <div class="col-sm-12">
                    <h4>Summary of Sales - Government Taxes</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- PARAMS --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    {!! Form::open(['route' => 'bills.government-tax-report', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ isset($_GET['ServicePeriod']) && $months[$i]==$_GET['ServicePeriod'] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Town</label>
                            <select id="Town" name="Town" class="form-control">
                                {{-- <option value="All">All</option> --}}
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Route</label>
                            <select class="custom-select select2" name="Route" id="Route">
                                
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 60vh;">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-bordered table-head-fixed text-nowrap">
                        <thead>
                            <th>Account No.</th>
                            <th>Consumer Name</th>
                            <th>Kwh Used</th>
                            {{-- <th>Energy</th> --}}
                            <th>Gen. VAT</th>
                            <th>Trans. VAT</th>
                            <th>Sys. Loss VAT</th>
                            <th>Dist. VAT</th>
                            <th>2% EWT</th>
                            <th>5% EWT</th>
                            <th>Gross Total</th>
                            <th>Net Total</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->OldAccountNo }}</td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ $item->KwhUsed }}</td>
                                    {{-- <td>*</td> --}}
                                    <td class="text-right">{{ is_numeric($item->GenerationVAT) ? number_format($item->GenerationVAT, 2) : $item->GenerationVAT }}</td>
                                    <td class="text-right">{{ is_numeric($item->TransmissionVAT) ? number_format($item->TransmissionVAT, 2) : $item->TransmissionVAT }}</td>
                                    <td class="text-right">{{ is_numeric($item->SystemLossVAT) ? number_format($item->SystemLossVAT, 2) : $item->SystemLossVAT }}</td>
                                    <td class="text-right">{{ is_numeric($item->DistributionVAT) ? number_format($item->DistributionVAT, 2) : $item->DistributionVAT }}</td>
                                    <td class="text-right">{{ is_numeric($item->Evat2Percent) ? number_format($item->Evat2Percent, 2) : $item->Evat2Percent }}</td>
                                    <td class="text-right">{{ is_numeric($item->Evat5Percent) ? number_format($item->Evat5Percent, 2) : $item->Evat5Percent }}</td>
                                    <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format(floatval($item->NetAmount) + floatval($item->Evat2Percent) + floatval($item->Evat5Percent), 2) : '' }}</td>
                                    <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <p id="route">{{ isset($_GET['Route']) ? $_GET['Route'] : null }}</p>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            fetchRoutes($('#Town').val())

            $('#Town').on('change', function() {
                fetchRoutes(this.value)
            })

            $('#print-btn').on('click', function(e) {
                e.preventDefault()
                if (jQuery.isEmptyObject($('#route').text())) {
                    Swal.fire({
                        title : 'No route selected',
                        icon : 'warning'
                    })
                } else {
                    window.location.href = "{{ url('/bills/print-government-tax-report') }}"+ "/" + $('#ServicePeriod').val() + "/"  + $('#Town').val() + "/" + $('#route').text()
                }
            })
        })

        function fetchRoutes(town) {
            $('#Route option').remove()
            $.ajax({
                url : "{{ route('bills.get-routes-from-town') }}",
                type : 'GET',
                data : {
                    Town : town,
                },
                success : function(res) {
                    $('#Route').html(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting routes',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush