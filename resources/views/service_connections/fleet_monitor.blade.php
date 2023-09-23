@extends('layouts.app')

@section('content')
    <section>

    </section>

    <div id="map"  style="width: 100%; height: 88vh;"></div>
@endsection

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [123.973103, 9.909745], // starting position [lng, lat]
            zoom: 12 // starting zoom
        });

        const marker1 = new mapboxgl.Marker()
                .setLngLat([123.877309, 9.891799])
                .addTo(map);

                new mapboxgl.Marker()
                .setLngLat([123.977259, 9.949275])
                .addTo(map);

                new mapboxgl.Marker()
                .setLngLat([123.976707, 9.949239])
                .addTo(map);

                new mapboxgl.Marker()
                .setLngLat([123.805265, 9.619035])
                .addTo(map);

                new mapboxgl.Marker()
                .setLngLat([123.939079, 9.613674])
                .addTo(map);

        const marker2 = new mapboxgl.Marker()
                .setLngLat([124.091388, 9.911926])
                .addTo(map);

        const marker3 = new mapboxgl.Marker({ color : 'red', })
                .setLngLat([124.009151, 9.922765])
                .addTo(map);

        const marker4 = new mapboxgl.Marker({ color : 'red', })
                .setLngLat([123.945467, 9.892833])
                .addTo(map);

                new mapboxgl.Marker({ color : 'red', })
                .setLngLat([123.946062, 9.915609])
                .addTo(map);

                new mapboxgl.Marker({ color : 'red', })
                .setLngLat([123.994945, 9.839239])
                .addTo(map);

                new mapboxgl.Marker({ color : 'red', })
                .setLngLat([124.110577, 9.899276])
                .addTo(map);

                new mapboxgl.Marker({ color : 'red', })
                .setLngLat([124.154228, 9.797528])
                .addTo(map);

        const marker5 = new mapboxgl.Marker()
                .setLngLat([123.944094, 9.894355])
                .addTo(map);
    </script>
@endpush