@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Create New Group Billing</h4>
                    <i class="text-muted">Step 2. Attach Service Accounts</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- SEARCH --}}
    <div class="col-lg-6">
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Search Acccount (Name, Account Number, etc)" id="search-field">
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-primary" id="search-btn"><i class="fas fa-search ico-tab-mini"></i>Search</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover" id="res-table">
                    <thead>
                        <th>Account ID</th>
                        <th>Account No.</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ATTACHED --}}
    <div class="col-lg-6">
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Existing Accounts in this Group</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Account ID</th>
                        <th>Account No.</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->OldAccountNo }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td class="text-right">
                                    {{-- <button onclick='deleteFromGroup("{{ $item->id }}")' class="btn btn-link text-danger"><i class="fas fa-trash"></i></button> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('bills.grouped-billing') }}" class="btn btn-default">Done</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#search-field').on('keyup', function() {
                var letterCount = this.value.length;

                if (letterCount > 5) {
                    performSearch(this.value)
                }
            })
        })

        function performSearch(regex) {
            $.ajax({
                url : '{{ route("bills.search-account") }}',
                type : 'GET',
                data : {
                    query : regex,
                },
                success : function(res) {
                    try {
                        if (jQuery.isEmptyObject(res)) {
                            $('#res-table tbody tr').remove()
                        } else {
                            $('#res-table tbody tr').remove()
                            $('#res-table tbody').append(res)
                        }   
                    } catch (err) {
                        $('#res-table tbody tr').remove()
                    }                                     
                },
                error : function(error) {
                    $('#res-table tbody tr').remove()
                    alert('Error fetching data')
                    console.log(error)
                }
            })
        }

        function addToGroup(id) {
            $.ajax({
                url : '{{ route("bills.add-to-group") }}',
                type : "GET",
                data : {
                    id : id,
                    MemberConsumerId : "{{ $memberConsumerId }}",
                },
                success : function(res) {
                    location.reload()
                },
                error : function(err) {
                    alert('An error occurred while attempting to add this account')
                }
            })
        }

        function deleteFromGroup(id) {
            if (confirm('Are you sure you want to remove this account from this group?')) {
                $.ajax({
                    url : '{{ route("bills.remove-from-group") }}',
                    type : "GET",
                    data : {
                        id : id,
                        MemberConsumerId : "{{ $memberConsumerId }}",
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while attempting to add this account')
                    }
                })
            }
        }
    </script>
@endpush