@extends('layouts.app')
@php
    use App\Models\TicketsRepository;
@endphp
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $ticketsRepository->Name }}</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('ticketsRepositories.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Details</span>

                <div class="card-tools">
                    <a href="{{ route('ticketsRepositories.edit', [$ticketsRepository->id]) }}" class="btn btn-tool" title="Edit {{ $ticketsRepository->Name }}"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-hover">
                        <tr>
                            <th>Description</th>
                            <td>{{ $ticketsRepository->Description }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $ticketsRepository->Type }}</td>
                        </tr>
                        @if ($ticketsRepository->ParentTicket != null)
                        <tr>
                            <th>Parent Ticket</th>
                            <td>{{ TicketsRepository::find($ticketsRepository->ParentTicket)->Name; }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>KPS Categorization</th>
                            <td>{{ $ticketsRepository->KPSCategory }}</td>
                        </tr>
                    </table>
                </div>

                @if ($ticketsRepository->Type == null)
                    <div class="divider"></div>

                    <p><i>Tickets under this category</i></p>

                    @php
                        $tickets = TicketsRepository::where('ParentTicket', $ticketsRepository->id)->get();
                    @endphp
                    <table class="table table-hover table-sm table-borderless">
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->Name }}</td>
                                <td width="120">
                                    {!! Form::open(['route' => ['ticketsRepositories.destroy', $ticket->id], 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        <a href="{{ route('ticketsRepositories.show', [$ticket->id]) }}"
                                           class='btn btn-default btn-xs'>
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a href="{{ route('ticketsRepositories.edit', [$ticket->id]) }}"
                                           class='btn btn-default btn-xs'>
                                            <i class="far fa-edit"></i>
                                        </a>
                                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure you want to delete this ticket type?')"]) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
                
            </div>
        </div>
    </div>
@endsection
