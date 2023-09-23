@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Ticket Crew Assigning</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            @include('flash::message')

            <div class="clearfix"></div>

            <p>Tickets (Complains) to be assigned</p>
            <table class="table table-hover">
                <thead>
                    <th>Ticket No</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Complain</th>
                    <th>Reason</th>
                    <th>Crew Assigning</th>
                    <th width="40px"></th>
                </thead>
                <tbody>
                    @if ($tickets != null)
                        @foreach ($tickets as $item)
                            @php
                                $ticketMain = TicketsRepository::find($item->TicketID);
                                $parent = TicketsRepository::where('id', $ticketMain->ParentTicket)->first();
                            @endphp
                            <tr>
                                <td><a href="{{ route('tickets.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->ConsumerName }}</td>
                                <td>{{ Tickets::getAddress($item) }}</td>
                                <th>{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $item->Ticket }}</th>
                                <td>{{ $item->Reason }}</td>
                                <td>
                                    <select id="crew-{{ $item->id }}" class="form-control">
                                        @foreach ($crew as $crews)
                                            <option value="{{ $crews->id }}">{{ $crews->StationName }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button onclick="changeCrew('{{ $item->id }}')" class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function changeCrew(id) {
            $.ajax({
                url : "/tickets/update-ordinary-ticket-assessment",
                type : 'POST',
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                    CrewAssigned : $('#crew-' + id).val(),
                },
                success : function(res) {
                    location.reload()
                },
                error : function(err) {
                    alert('An error occurred while attempting to change crew. Contact crew for more!')
                }
            })
        }
    </script>
@endpush