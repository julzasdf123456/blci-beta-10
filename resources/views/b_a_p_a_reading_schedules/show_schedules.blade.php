@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>BAPA Schedule for {{ date('F Y', strtotime($period)) }}</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('bAPAReadingSchedules.create') }}">
                        Create New Schedule
                    </a>
                </div>
            </div>
        </div>
    </section>

<div class="row">
    {{-- TOWNS --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Districts/Towns</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover">
                    <thead>
    
                    </thead>
                    <tbody>
                        @foreach ($towns as $item)
                            <tr onclick="showBapas('{{ $item->id }}', '{{ $item->Town }}')">
                                <td>{{ $item->Town }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- BAPAS --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title" id="town-name">All BAPA in this Schedule</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover" id="res-table">
                    <thead>
                        <th>BAPA</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            showBapas(null, 'All')
        })

        function showBapas(id, town) {
            $('#town-name').text(town)
            $('#res-table tbody tr').remove()
            $.ajax({
                url : "{{ route('bAPAReadingSchedules.get-bapas') }}",
                type : 'GET',
                data : {
                    Town : id,
                    Period : "{{ $period }}",
                },
                success : function(res) {
                    $('#res-table tbody').append(res)
                },
                error : function(err) {
                    alert('An error occured while getting BAPAS')
                }
            })
        }

        function removeStatus(id) {
            $.ajax({
                url : "{{ route('bAPAReadingSchedules.remove-downloaded-status-from-bapa') }}",
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error cancelling status',
                        icon : 'error'
                    })
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