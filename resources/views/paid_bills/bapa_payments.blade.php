@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>BAPA Bills Payment Console</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3 offset-lg-3">
                <input type="text" id="search-field" placeholder="Search BAPA Name" class="form-control">
            </div>
            <div class="col-lg-2">
                <select name="" id="towns" class="form-control">
                    <option value="All">All</option>
                    @foreach ($towns as $item)
                        <option value="{{ $item->id }}">{{ $item->Town }}</option>
                    @endforeach
                </select>
            </div>
            <div class="class-col-lg-2">
                <button class="btn btn-primary" id="search-btn"><i class="fas fa-search ico-tab"></i>Search</button>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-10 offset-md-1">
                <br>

                <table class="table table-hover" id="res-table">
                    <thead>
                        <th>BAPA Name</th>
                        <th>Town/Area/District</th>
                        <th>Number of Accounts</th>
                        <th>Routes in This BAPA</th>
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
            $('#search-field').keyup(function() {
                var len = this.value.length

                if (len > 3) {
                    searchBapa(this.value, $('#towns').val())
                }
            })

            $('#towns').on('change', function() {
                searchBapa($('#search-field').val(), this.value)
            })

            $('#search-btn').on('click', function() {
                searchBapa($('#search-field').val(), $('#towns').val())
            })
        })

        function searchBapa(param, town) {
            $('#res-table tbody tr').remove()
            $.ajax({
                url : "{{ route('paidBills.search-bapa') }}",
                type : 'GET',
                data : {
                    BAPA : param,
                    Town : town,
                },
                success : function(res) {
                    $('#res-table tbody').append(res)
                },
                error : function(err) {
                    alert('An error occurred during the search')
                }
            })
        }
    </script>
@endpush