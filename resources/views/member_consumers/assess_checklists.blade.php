@php
    use App\Models\MemberConsumers;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong><span class="badge-lg bg-success">Step 2</span>Membership Checklist Assessment</strong></p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-12">
            <div class="content px-3">

                @include('adminlte-templates::common.errors')

                <div class="card">
                    <div class="card-header">
                        <span><strong>Did {{ MemberConsumers::serializeMemberName($memberConsumers) }} submit the following requirements?</strong></span>
                    </div>

                    

                    <div class="card-body">

                        <div class="row">
                            {!! Form::open(['route' => ['memberConsumerChecklists.comply-checklists', $memberConsumers->Id], 'class' => "form-horizontal"]) !!}
                            @if ($checklist == null)
                                <p class="text-center">No cheklist found. Go to Settings and add Checklists.</p>
                            @else
                                @foreach ($checklist as $item)
                                    <div class='form-check'>
                                        <input type="checkbox" class='form-check-input' value='{{ $item->id }}' name="ChecklistId[]">
                                
                                        {{ Form::label('item', $item->Checklist, ['class' => 'form-check-label']) }}
                                    </div>   
                                @endforeach
                            @endif
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save and Proceed', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    
@endsection
