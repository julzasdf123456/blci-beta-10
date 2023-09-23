@php
    use App\Models\TicketsRepository;
@endphp

<div id="accordion">
    @foreach ($ticketsRepositories as $item)
        <div class="card mb-0">
            <div class="card-header" id="heading{{ $item->id }}">
                <h5 class="mb-0 card-title">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#id{{ $item->id }}" aria-expanded="false" aria-controls="id{{ $item->id }}">
                    {{ $item->Name }}
                    </button>
                </h5>

                <div class="card-tools">
                    <a href="{{ route('ticketsRepositories.show', [$item->id]) }}" class="btn btn-tool" title="View {{ $item->Name }}"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('ticketsRepositories.edit', [$item->id]) }}" class="btn btn-tool" title="Edit {{ $item->Name }}"><i class="fas fa-edit"></i></a>
                </div>
            </div>
        
            <div id="id{{ $item->id }}" class="collapse" aria-labelledby="heading{{ $item->id }}" data-parent="#accordion">
                <div class="card-body">
                    @php
                        $tickets = TicketsRepository::where('ParentTicket', $item->id)->get();
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
                </div>
            </div>
        </div>
    @endforeach
</div>

