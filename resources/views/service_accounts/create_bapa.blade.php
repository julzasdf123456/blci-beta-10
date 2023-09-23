@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Create New BAPA</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- CONFIG --}}
    <div class="col-lg-5 col-md-6">
        <div class="card" style="height: 70vh;">
            <div class="card-header">
                <span class="card-title">Configuration</span>
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="BAPAName">BAPA Name</label>
                        <input type="text" id="BAPAName" class="form-control" placeholder="Input BAPA Name" maxlength="30">
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="Town">Fetch Route From District</label>
                        <select name="Town" id="Town" class="form-control">
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}">{{ $item->id }} - {{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="divider"></div>
                    <p><strong>Area/Routes in this District (Press F3 to search)</strong></p>
                    <table class="table table-hover table-sm" id="route-table">
                        <thead>
                            <th>Route/Area Code</th>
                            <th></th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{ route('serviceAccounts.bapa') }}">Done</a>
            </div>
        </div>
    </div>

    {{-- MEMBERS --}}
    <div class="col-lg-7 col-md-6">
        <div class="card" style="height: 70vh;">
            <div class="card-header">
                <span class="card-title">Accounts</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover" id="accounts-table">
                    <thead>
                        <th>Account ID</th>
                        <th>Account Number</th>
                        <th>Consumer Name</th>
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
        $(document).ready(function() {
            getRoutes($('#Town').val())

            $('#Town').on('change', function() {
                getRoutes(this.value)
            })
        })

        function getRoutes(town) {
            $('#route-table tbody tr').remove()
            $.ajax({
                url : '{{ route("serviceAccounts.get-routes-from-district") }}',
                type : 'GET',
                data : {
                    Town : town
                },
                success : function(res) {
                    $('#route-table tbody').append(res)
                },
                error : function(err) {
                    alert('An error occurred while fetching routes')
                }
            })
        }

        function addToBapa(route) {
            $('#accounts-table tbody tr').remove()
            var bapaName = $('#BAPAName').val()
            if (jQuery.isEmptyObject(bapaName)) {
                alert('Input BAPA Name first')
            } else {
                $.ajax({
                    url : '{{ route("serviceAccounts.add-to-bapa") }}',
                    type : 'GET',
                    data : {
                        AreaCode : route,
                        BAPAName : bapaName,
                        Town : $('#Town').val()
                    },
                    success : function(res) {
                        console.log(res)
                        $('#accounts-table tbody').append(res)
                    },
                    error : function (error) {
                        alert('An error occurred while adding BAPA')
                    }
                })
            }
        }
    </script>
@endpush