@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
    use App\Models\Users;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4><i class="fas fa-trash ico-tab"></i>Ticket Trash</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="px-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover">
                            <thead>
                                <th>Ticket No.</th>
                                <th>Account No.</th>
                                <th>Consumer Name</th>
                                <th>Address</th>
                                <th>Ticket</th>
                                <th>Deleted By</th>
                                <th width="40px"></th>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $item)
                                    @php
                                        $parent = TicketsRepository::where('id', $item->ParentTicket)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->AccountNumber }}</td>
                                        <td>{{ $item->ConsumerName }}</td>
                                        <td>{{ Tickets::getAddress($item) }}</td>
                                        <td>{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $item->Ticket }}</td>
                                        <td>{{ Users::find($item->UserId)->name }}</td>
                                        <td>
                                            <a href="{{ route('tickets.restore-ticket', [$item->id]) }}" title="Restore this ticket"><i class="fas fa-redo-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection