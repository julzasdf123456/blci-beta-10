@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account Group Edit | Day {{ $day }} | {{ $town!= null ? $town->Town : '-' }}</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- METER READERS --}}
    <div class="col-lg-3">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-header">
                <span class="card-title">Meter Readers</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <th>Meter Reader</th>
                        <th>No. of Consumers</th>
                    </thead>
                    <tbody>
                        @foreach ($meterReaders as $item)
                            <tr onclick="fetchRoutes('{{ $item->MeterReader }}')">
                                <td>{{ $item->name != null ? $item->name : 'Unassigned' }}</td>
                                <td>{{ $item->NoOfConsumers }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ROUTES --}}
    <div class="col-lg-9">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-header">
                <span class="card-title">Routes Within the Selected Meter Reader</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover" id="routes-table">
                    <thead>
                        <th>Route Code</th>
                        <th>Transfer To (Meter Reader -> Day)</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var mreader = null
        var day = null
        $(document).ready(function() {

        })

        function fetchRoutes(meterReader) {
            $('#routes-table tbody tr').remove()
            mreader = meterReader
            if (!jQuery.isEmptyObject(meterReader)) {
                day = '{{ $day }}'
            } else {
                day = null
            }
            $.ajax({
                url : "{{ route('serviceAccounts.fetch-route-from-mreader') }}",
                type : 'GET',
                data : {
                    Day : '{{ $day }}',
                    MeterReader : meterReader,
                    Town : '{{ $town->id }}'
                },
                success : function(res) {
                    $('#routes-table tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting routes',
                        icon : 'error'
                    })
                }
            })
        }

        function moveRoute(areacode) {
            $.ajax({
                url : '{{ route("serviceAccounts.move-route") }}',
                type : 'GET',
                data : {
                    OriginalDay : day,
                    OriginalMeterReader : mreader,
                    Town : '{{ $town->id }}',
                    NewDay : $('#' + areacode + "-day").val(),
                    NewMeterReader : $('#' + areacode + "-mreader").val(),
                    Route : areacode
                },
                success : function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Route transferred!'
                    })
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error trasnfering route',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush