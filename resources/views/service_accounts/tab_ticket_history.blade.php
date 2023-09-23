@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="card" style="height: 60vh;">
            <div class="card-header border-0">
                <span class="card-title">Complains and Request</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Complaint/Request</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                        @foreach ($complaints as $item)
                            @php
                                $ticketMain = TicketsRepository::find($item->TicketID);
                                $parent = TicketsRepository::where('id', $ticketMain->ParentTicket)->first();
                            @endphp
                            <tr>
                                <td><a href="{{ route('tickets.show', $item->id) }}">{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $item->Ticket }}</a></td>
                                <td>{{ $item->Reason }}</td>
                                <td>{{ date('F d, Y, h:i A', strtotime($item->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">
        <div class="card" style="height: 60vh;">
            <div class="card-header border-0">
                <span class="card-title">Violations</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Violation</th>
                        <th>Date Captured</th>
                    </thead>
                    <tbody>
                        @foreach ($violations as $item)
                            @php
                                $ticketMain = TicketsRepository::find($item->TicketID);
                                $parent = TicketsRepository::where('id', $ticketMain->ParentTicket)->first();
                            @endphp
                            <tr>
                                <td><a href="{{ route('tickets.show', $item->id) }}">{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $item->Ticket }}</a></td>
                                <td>{{ date('F d, Y, h:i A', strtotime($item->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>