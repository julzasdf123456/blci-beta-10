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
                        <td><a href="{{ URL::asset("scfiles/" . $serviceConnections->id . "/" . $item) }}" target="_blank">{{ $item }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>