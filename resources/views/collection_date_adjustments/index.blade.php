@php
    use App\Models\CollectionDateAdjustments;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="no-pads">Teller Collection Date Adjustments</h4>
                    <p class="no-pads text-muted">Teller and Cashier Collection Date Configuration</p>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Tellers</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm table-borderless">
                    <thead>
                        <th>Teller/Cashier</th>
                        <th>Active Collection Date</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($tellers as $item)
                            @php
                                $collectionDate = CollectionDateAdjustments::whereRaw("UserId='" . $item->id . "'")
                                    ->orderByDesc('created_at')
                                    ->first();

                                if ($collectionDate != null) {
                                    if ($collectionDate->DateAssigned >= date('Y-m-d')) {
                                        $effectiveDate = date('Y-m-d', strtotime($collectionDate->DateAssigned));
                                    } else {
                                        $effectiveDate = date('Y-m-d');
                                    }
                                } else {
                                    $effectiveDate = date('Y-m-d');
                                }
                            @endphp
                            <tr>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td>
                                    <input type="date" class="form-control form-control-sm" id="schedule-{{ $item->id }}" placeholder="Collection Date" value="{{ $effectiveDate }}">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="setSched(`{{ $item->id }}`)"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('page_scripts')
    <script>
        function setSched(id) {
            var date = moment($('#schedule-' + id).val()).format('YYYY-MM-DD')
            $.ajax({
                url : "{{ route('collection-date-adjustments.store') }}",
                type : 'POST',
                data : {
                    _token : "{{ csrf_token() }}",
                    UserId : id,
                    DateAssigned : date,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Schedule saved!'
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error setting schedule!'
                    })
                }
            })
        }
    </script>
@endpush
