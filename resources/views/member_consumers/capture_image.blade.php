@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Set Consumer Image</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <video class="card-img-top" id="video" width="480" height="480" autoplay></video>
                    <div class="card-body">                        
                        <button class="btn btn-default" id="snap"><i class="fas fa-camera"></i> Snap Photo</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <canvas id="canvas" class="card-img-top" width="480" height="480"></canvas>
                    <div class="card-body">                        
                        <button class="btn btn-success" id="save"><i class="fas fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var snapped = false;
        $(document).ready(function() {
            // Grab elements, create settings, etc.
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            var video = document.getElementById('video');

            // Get access to the camera!
            if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                // Not adding `{ audio: true }` since we only want video now
                navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                    //video.src = window.URL.createObjectURL(stream);
                    video.srcObject = stream;
                    video.play();
                });
            }

            /* Legacy code below: getUserMedia 
            else if(navigator.getUserMedia) { // Standard
                navigator.getUserMedia({ video: true }, function(stream) {
                    video.src = stream;
                    video.play();
                }, errBack);
            } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
                navigator.webkitGetUserMedia({ video: true }, function(stream){
                    video.src = window.webkitURL.createObjectURL(stream);
                    video.play();
                }, errBack);
            } else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
                navigator.mozGetUserMedia({ video: true }, function(stream){
                    video.srcObject = stream;
                    video.play();
                }, errBack);
            }
            */

            $('#snap').on('click', function() {
                context.drawImage(video, 0, 0, 640, 480);
                snapped = true;
            });

            $('#save').on('click', function() {
                var fullQuality = canvas.toDataURL('image/jpeg', 1.0);

                if (!snapped) {
                    alert('Capture image first!');
                } else {
                    // SAVE IMAGE
                    $.ajax({
                        url : '/member_consumer_images/create-image/',
                        type : 'POST',
                        data : {
                            _token : "{{ csrf_token() }}",
                            ConsumerId : "{{ $id }}",
                            HexImage : fullQuality,
                        },
                        success : function(result) {
                            window.location.replace("{{ route('memberConsumers.show', [$id]) }}");
                        },
                        error : function(error) {
                            alert('Error saving image! See console for details');
                            console.log(error);
                        }
                    });
                }
            })
        })
    </script>
@endpush