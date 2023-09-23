@php
    use App\Models\IDGenerator;
    use App\Models\ServiceConnectionChecklists;
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong><span class="badge-lg bg-warning">Step 4</span>Service Connection Checklist Assessment</strong></p>
                </div>
            </div>
        </div>
    </section>
    {{-- UPLOAD REQUIREMENTS --}}
    {{-- <div class="row">
        <div class="col-lg-10 offset-lg-1 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <span><strong>Upload requirements for {{ $serviceConnections->ServiceAccountName }}</strong></span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>

                        </thead>
                        <tbody>
                            @if ($checklist == null)
                                <p class="text-center">No cheklist found. Go to Settings and add Checklists.</p>
                            @else
                                @foreach ($checklist as $item)
                                    @php
                                        // FETCH CHECKLIST RECORD IF THERE'S ALREADY AN EXISTING RECORD
                                        $checkListRecord = ServiceConnectionChecklists::where('ServiceConnectionId', $serviceConnections->id)
                                            ->where('ChecklistId', $item->id)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($checkListRecord != null)
                                                <i class="fas fa-check-circle text-success ico-tab"></i>{{ $item->Checklist }} 
                                                (<a href="{{ route('serviceConnectionChecklists.download-file', [$serviceConnections->id, $item->Checklist, $checkListRecord->Notes]) }}" target="_blank">{{ $checkListRecord->Notes }}</a>)
                                            @else
                                                <i class="fas fa-info-circle text-warning ico-tab"></i>{{ $item->Checklist }}                                                
                                            @endif
                                            
                                        </td>
                                        <td class="text-right">
                                            @if ($checkListRecord != null)
                                                {!! Form::open(['route' => ['serviceConnectionChecklists.destroy', $checkListRecord->id], 'method' => 'delete']) !!}
                                                    {!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn text-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <form method="POST" enctype="multipart/form-data" id="upload-{{ $item->id }}" action="javascript:void(0)" >
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="checklistId" value="{{ $item->id }}">
                                                    <input type="hidden" name="scId" value="{{ $serviceConnections->id }}">
                                                    <input type="hidden" name="folder" value="{{ $item->Checklist }}">
                                                    <div class="form-group">
                                                        <input type="file" name="file" placeholder="Choose File" id="file-{{ $item->id }}">
                                                        <span class="text-danger">{{ $errors->first('file') }}</span>
                                                    </div>
                                                    <button type="submit" onclick="uploadData({{ $item->id }})" class="btn btn-sm btn-primary float-right">Upload</button>    
                                                </form>
                                            @endif                                            
                                        </td>
                                    </tr> 
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('serviceConnectionChecklists.assess-checklist-completion', [$serviceConnections->id]) }}" class="btn btn-primary">Save and Proceed</a>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- CHECKLIST ONLY --}}
    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-12">
            <div class="content px-3">

                @include('adminlte-templates::common.errors')

                <div class="card">
                    <div class="card-header">
                        <span><strong>Did {{ $serviceConnections->ServiceAccountName }} submit the following requirements?</strong></span>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            {!! Form::open(['route' => ['serviceConnectionChecklists.comply-checklists', $serviceConnections->id], 'class' => "form-horizontal"]) !!}
                            @if ($checklist == null)
                                <p class="text-center">No cheklist found. Go to Settings and add Checklists.</p>
                            @else
                                @foreach ($checklist as $item)
                                    @php
                                        // FETCH CHECKLIST RECORD IF THERE'S ALREADY AN EXISTING RECORD
                                        $checkListRecord = ServiceConnectionChecklists::where('ServiceConnectionId', $serviceConnections->id)
                                            ->where('ChecklistId', $item->id)
                                            ->first();
                                    @endphp
                                    <div class='form-check'>
                                        <input type="checkbox" class='form-check-input' value='{{ $item->id }}' name="ChecklistId[]" {{ $checkListRecord != null ? 'checked' : '' }}>
                                
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

@push('page_scripts')
    <script>
        function uploadData(id) {
            var formData = new FormData(document.getElementById('upload-' + id))
        
            $.ajax({
                url : '/service_connection_checklists_reps/save-file-and-comply-checklist',
                type : 'POST',
                data : formData,
                cache : false,
                contentType : false, 
                processData : false,
                success : function(response) {
                    // alert('Data uploaded!')
                    // console.log(response)
                    location.reload();
                },
                error : function(error) {
                    alert(error)
                }
            })
        }     
    </script>
@endpush
