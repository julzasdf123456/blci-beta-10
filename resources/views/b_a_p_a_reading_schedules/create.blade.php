@php
    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Create New BAPA Reading Schedule</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">

    @include('adminlte-templates::common.errors')

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-3 offset-lg-2 col-md-5">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Config</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="ServicePeriod">Select Billing Month</label>
                            <select name="ServicePeriod" id="ServicePeriod" class="form-control">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="Town">Select Town</label>
                            <select name="Town" id="Town" class="form-control">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}">{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" id="createBtn">Create</button>
                </div>
            </div>
        </div>

        {{-- RESULT TABLE --}}
        <div class="col-lg-5 col-md-7">
            <div class="card" style="height: 80vh;">
                <div class="card-header">
                    <span class="card-title">BAPAs in this Schedule</span>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm" id="res-table">
                        <thead>
                            <th>BAPA Name</th>
                            <th></th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('bAPAReadingSchedules.index') }}" class="btn btn-default">Done</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#createBtn').on('click', function() {
                addSchedule()
            })
        })

        function addSchedule() {
            $('#res-table tbody tr').remove()
            var period = $('#ServicePeriod').val()
            var town = $('#Town').val()

            $.ajax({
                url : '{{ route("bAPAReadingSchedules.add-schedule") }}',
                type : 'GET',
                data : {
                    Period : period,
                    Town : town,
                },
                success : function(res) {
                    $('#res-table tbody').append(res)
                },
                error : function(err) {
                    alert('Error adding schedule. Contact support for details')
                }
            })
        }

        function removeBapaFromSched(id) {
            if (confirm('Are you sure you want to remove this BAPA from this schedule?')) {
                $.ajax({
                    url : "{{ route('bAPAReadingSchedules.remove-bapa-from-sched') }}",
                    type : 'GET',
                    data : {
                        id : id
                    },
                    success : function(res) {
                        $('#' + id).remove()
                    },
                    error : function(err) {
                        alert('An error occurred while trying to remove this BAPA from sched')
                    }
                })
            }
        }
    </script>
@endpush
