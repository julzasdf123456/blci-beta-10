@php
    use Illuminate\Support\Facades\Storage;

    $files = Storage::disk('public')->allFiles('/documents/' . $serviceConnections->id . '/images');
@endphp

@push('page_css')
    <style>
        .image-box {
            display: inline-block;
        }

        .images-application {
            width: 48%;
            display: inline;
            margin: 2px;
        }
    </style>
@endpush

<div class="image-box">
    @if ($files != null)
        @foreach ($files as $item)
            <img class="images-application" src="{{ url('/storage/' . $item) }}" alt="">
        @endforeach        
    @endif
</div>

<div class="row">
    @foreach ($images as $item)
        <div class="col-lg-3">
            <img src="data:image/png;base64,{{ $item->Signature }}" alt="Photo" style="width: 100%;">
        </div>
    @endforeach
</div>
