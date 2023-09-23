@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Tracks for {{ $meterReaderTrackNames->TrackName }}</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('meterReaderTrackNames.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div>
        <span style="margin-right: 20px;"><strong>Legend:</strong></span>
        <span style="border-left: 20px solid blue; padding-left: 10px; margin-right: 20px;">Start</span>
        <span style="border-left: 20px solid red; padding-left: 10px; margin-right: 20px;">End</span>
    </div>
    <div id="map"  style="width: 100%; height: 80vh;"></div>
@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [123.242197, 10.844679], // starting position [lng, lat], 
            zoom: 10 // starting zoom
        });

        map.on('load', () => {
            $.ajax({
                url : '/meter_reader_tracks/get-tracks-by-tracknameid',
                type : 'GET',
                data : {
                    TrackNameId : "{{ $meterReaderTrackNames->id }}"
                }, 
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        alert('No tracks recorded in this track set')
                    } else {
                        var coordinates = [];

                        $.each(res, function(index, element) {
                            coordinates.push([res[index]['Longitude'], res[index]['Latitude']])
                        })

                        map.addSource('route', {
                            'type': 'geojson',
                            'lineMetrics': true,
                            'data': {
                                'type': 'Feature',
                                'properties': {},
                                'geometry': {
                                    'type': 'LineString',
                                    'coordinates': coordinates
                                }
                            }
                        });

                        map.addLayer({
                            'id': 'route',
                            'type': 'line',
                            'source': 'route',
                            'layout': {
                                'line-join': 'round',
                                'line-cap': 'round'
                            },
                            'paint': {
                                'line-color': 'red',
                                'line-width': 8,
                                'line-gradient': [
                                    'interpolate',
                                    ['linear'],
                                    ['line-progress'],
                                    0,
                                    'blue',
                                    0.1,
                                    'royalblue',
                                    0.3,
                                    'cyan',
                                    0.5,
                                    'lime',
                                    0.7,
                                    'yellow',
                                    1,
                                    'red'
                                ]
                            }
                        });

                        map.flyTo({
                            center: coordinates[0],
                            zoom: 15,
                            bearing: 0,
                            speed: 1, // make the flying slow
                            curve: 1, // change the speed at which it zooms out
                            easing: (t) => t,
                            essential: true
                        });

                        new mapboxgl.Marker({ color: 'blue', rotation: 45 })
                            .setLngLat(coordinates[0])
                            .addTo(map);
                        
                        new mapboxgl.Marker({ color: 'red', rotation: -45 })
                            .setLngLat(coordinates[coordinates.length-1])
                            .addTo(map);
                    }
                },
                error : function(err) {
                    alert('Error fetching tracks! Contact support for more.')
                    console.log(err)
                }
            })
        })
    </script>
@endpush
