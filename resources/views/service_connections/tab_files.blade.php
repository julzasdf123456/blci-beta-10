@php
    use App\Models\ServiceConnections;
@endphp

<div class="row p-2">
    <div class="col-lg-12">
        <a href="{{ route('serviceConnections.upload-files', [$serviceConnections->id]) }}" class="btn btn-sm btn-primary float-right mb-3"><i class="fas fa-upload ico-tab-mini"></i>Upload Files</a>

        <table class="table table-hover">
            <tbody>
                @php
                    $imgs = ['jpg', 'jpeg', 'png', 'webp', 'bmp'];
                    $pdfs = ['pdf'];
                @endphp
                @foreach ($fileNames as $item)
                    @php
                        $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                    @endphp
                    @if ($item !== 'images')
                    <tr>
                        <td style="width: 30px;">
                            @if (in_array($extension, $imgs))
                                <i class="fas fa-image"></i>
                            @elseif (in_array($extension, $pdfs))
                                <i class="fas fa-file-pdf"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </td>
                        <td class="v-align"><a href="{{ URL::asset("scfiles/" . $serviceConnections->id . "/" . $item) }}" target="_blank">{{ $item }}</a></td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-link-muted btn-sm float-right" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                            
                                <div class="dropdown-menu">
                                    <button onclick="trash(`{{ $serviceConnections->id }}`, `{{ $item }}`)" class="dropdown-item"><i class="fas fa-trash ico-tab"></i>Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('page_scripts')
    <script>
        function trash(id, filename) {
            Swal.fire({
                text: "Are you sure you want to remove this file? This cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceed File Removal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('serviceConnections.trash-documents') }}",
                        type : "POST",
                        data : {
                            _token : "{{ csrf_token() }}",
                            ServiceConnectionId : id,
                            CurrentFile : filename,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'File deleted!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error deleting file'
                            })
                        }
                    })      
                }
            })
        }
    </script>
@endpush