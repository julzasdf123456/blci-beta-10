@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;"><?= $bapaName ?> 
                        <button class="btn btn-sm btn-link text-primary" style="margin-left: 10px;" id="rename-bapa" title="Rename BAPA"><i class="fas fa-pen"></i></button>
                    </h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- ROUTES --}}
    <div class="col-lg-4 col-md-5">
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Routes/Area Codes in this BAPA</span>
                <div class="card-tools">
                    <a href="{{ route('serviceAccounts.update-bapa', [urlencode($bapaName)]) }}" class="btn btn-sm btn-primary">Add Routes</a>
                </div>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Route/Area</th>
                        <th>No. of Consumers</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($routes as $item)
                            <tr>
                                <td>{{ $item->AreaCode }}</td>
                                <td>{{ number_format($item->NoOfConsumers) }}</td>
                                <td class="text-right">
                                    <button onclick="removeByRoute('{{ $item->AreaCode }}')" class="btn btn-sm btn-link text-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ACCOUNTS --}}
    <div class="col-lg-8 col-md-7">
        <div class="card" style="height: 80vh;">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#readings" data-toggle="tab">
                        <i class="fas fa-unlink"></i>
                        Readings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#all-accounts" data-toggle="tab">
                        <i class="fas fa-file-invoice"></i>
                        All Accounts</a></li>
                </ul>
            </div>
            <div class="card-body table-responsive p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="readings">
                        @include('service_accounts.bapa_tab_readings')
                    </div>
                    <div class="tab-pane" id="all-accounts">
                        @include('service_accounts.bapa_tab_all_accounts')
                    </div>
                </div>
                
            </div>
            <div class="card-footer">
                <span>Total Consumers: <strong>{{ count($serviceAccounts) }}</strong></span>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR SEARCHING OF CONSUMERS --}}
<div class="modal fade" id="modal-search" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Search Consumer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- SEARCH --}}
                <div class="row">                    
                    <div class="form-group col-lg-8 offset-lg-1">
                        <input type="text" id="search" placeholder="Account Number, Account Name, or Meter Number" class="form-control" autofocus="true">
                    </div>
                    <div class="form-group col-lg-1">
                        <button id="search-consumer" class="btn btn-primary btn-lg"><i class="fas fa-search-dollar"></i></button>
                    </div>
                </div>

                {{-- RESULTS --}}
                <p class="text-muted"><i id="count">Results</i></p>
                <table class="table table-sm table-hover" id="res-table">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Address</th>
                        <th>Status</th>
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
        $(document).ready(function() {
            $('#rename-bapa').on('click', function() {
                renameBapa()
            }) 

            $('#search').keyup(function() {
                var letterCount = this.value.length;

                if (letterCount > 4) {
                    performSearch(this.value)
                }
            })
        })

        function removeByRoute(route) {
            if (confirm('Are you sure you want to delete this route from this BAPA?')) {
                $.ajax({
                    url : '/service_accounts/remove-bapa-by-route',
                    type : 'GET',
                    data : {
                        BAPAName : "{{ $bapaName }}",
                        Route : route,
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while trying to delete the route from the BAPA')
                    }
                })
            }            
        }

        function removeByAccount(id) {
            if (confirm('Are you sure you want to delete this account from this BAPA?')) {
                $.ajax({
                    url : '/service_accounts/remove-bapa-by-account',
                    type : 'GET',
                    data : {
                        AccountNumber : id,
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while trying to delete the account from the BAPA')
                    }
                })
            }            
        }

        function performSearch(regex) {
            $.ajax({
                url : '/service_accounts/search-accout-bapa',
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

        function addAccountToBapa(id) {
            $.ajax({
                url : '/service_accounts/add-single-account-to-bapa',
                type : 'GET',
                data : {
                    id : id,
                    BAPAName : "{{ $bapaName }}",
                },
                success : function(res) {
                    location.reload()
                },
                error : function(err) {
                    alert('An error occurred while adding the account to this BAPA')
                }
            })
        }

        function renameBapa() {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'text',
                    inputPlaceholder: 'New BAPA Name',
                    inputAttributes: {
                        'maxlength' : 30,
                    },
                    title: 'Rename This BAPA',
                    text : 'Are you sure to rename this BAPA? You might update the BAPA Reading App the next time this BAPA is going to be read.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : '{{ route("serviceAccounts.rename-bapa") }}',
                        type : 'GET',
                        data : {
                            OldBapaName : "{{ $bapaName }}",
                            NewBapaName : text
                        },
                        success : function(res) {
                            Swal.fire('Sucess', 'BAPA renaming successful!', 'success')
                            window.location.href = "{{ url('/service_accounts/bapa-view') }}" + "/" + encodeURIComponent(text)
                        },
                        error : function(err) {
                            Swal.fire('Oops!', 'An error occurred while renaming this BAPA. Contact support immediately', 'error')
                        }
                    })
                }
            })()
        }
    </script>
@endpush