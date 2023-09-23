<div class="modal fade" id="modal-view-map" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Map View</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col-lg-12">
                    <div id="map" style="height: 300px; width: 100%; position: relative;"></div>  
                </div>                
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            // style: 'mapbox://styles/mapbox/satellite-v9',
            style : 'mapbox://styles/julzlopez/ckahntemo048l1il7edks77wb',
            center: [parseFloat('{{ $serviceAccounts->Longitude }}'), parseFloat('{{ $serviceAccounts->Latitude }}')], // starting position [lng, lat] 
            zoom: 14 // starting zoom
        });

        var markers = [];

        map.on('load', () => {
            const el = document.createElement('div');
                el.className = 'marker';
                el.id = '{{ $serviceAccounts->id }}';
                el.title = '{{ $serviceAccounts->ServiceAccountName }}'
                el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 1.5em;"></i></span> </button>'
                el.style.backgroundColor = `transparent`;                       
                el.style.width = `45px`;
                el.style.height = `45px`;
                el.style.borderRadius = '50%';
                el.style.backgroundSize = '100%';

            el.addEventListener('click', () => {
                        Swal.fire({
                            title : '{{ $serviceAccounts->ServiceAccountName }}',
                            text : 'Latitude: {{ $serviceAccounts->Latitude }}, Longitude: {{ $serviceAccounts->Longitude }}',
                        })
                    });

            new mapboxgl.Marker(el)
                    .setLngLat([parseFloat('{{ $serviceAccounts->Longitude }}'), parseFloat('{{ $serviceAccounts->Latitude }}')])
                    .addTo(map);
        })

    </script>
@endpush