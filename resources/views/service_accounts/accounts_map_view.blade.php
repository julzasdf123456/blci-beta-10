@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-10">
        <div id="map" style="height: 88vh;"></div>  
    </div>
    <div class="col-lg-2">
        <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <span class="card-title">Filter</span>
                <div class="card-tools">
                    <div id="loader" class="spinner-border text-info gone" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    {!! Form::label('District', 'District') !!}
                    <select name="District" id="District" class="form-control">
                        <option>-- Select --</option>
                        <option value="All">All</option>
                        @foreach ($towns as $item)
                            <option value="{{ $item->id }}">{{ $item->Town }}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <span class="card-title">Search</span>                
            </div>
            <div class="card-body">
                <div class="form-group">
                    {!! Form::text('search', null, ['class' => 'form-control','id'=>'search', 'placeholder' => 'Acct. No / Name']) !!}
                </div>
                <button id="searchBtn" class="btn btn-sm btn-primary">Search</button>
                
                <div class="divider"></div>

                <table id="searchTable" class="table table-sm table-hover">
                    <thead></thead>
                    <tbody style="display: block; height: 300px; overflow-y: scroll"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            // style: 'mapbox://styles/mapbox/satellite-v9',
            style : 'mapbox://styles/julzlopez/ckahntemo048l1il7edks77wb',
            center: [123.254981, 10.785084], // starting position [lng, lat] 
            zoom: 10 // starting zoom
        });

        var markers = [];

        map.on('load', () => {
            // searchByTown()
        })

        function searchByTown(areaCode) {
            $('#loader').removeClass('gone')
            $.ajax({
                url : '{{ route("serviceAccounts.get-accounts-by-town") }}',
                type : 'GET',
                data : {
                    Town : areaCode,
                },
                success : function(res) {
                    if (markers.length > 0) {
                        for (x=0; x<markers.length; x++) {
                            markers[x].remove()
                        }
                    }
                    var i = 0;
                    $.each(res, function(index, element) {
                        // Create a DOM element for each marker.
                        const el = document.createElement('div');
                        el.className = 'marker';
                        el.id = res[index]['id'];
                        el.title = res[index]['ServiceAccountName']
                        el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger"></i></span> </button>'
                        el.style.backgroundColor = `transparent`;                       
                        el.style.width = `15px`;
                        el.style.height = `15px`;
                        el.style.borderRadius = '50%';
                        el.style.backgroundSize = '100%';

                        el.addEventListener('click', () => {
                            Swal.fire({
                                title : res[index]['ServiceAccountName'],
                                text : 'Latitude: ' + res[index]['Latitude'] + ', Longitude: ' + res[index]['Longitude'],
                            })
                        });

                        if (parseFloat(res[index]['Longitude'])) {
                            marker = new mapboxgl.Marker(el)
                                .setLngLat([parseFloat(res[index]['Longitude']), parseFloat(res[index]['Latitude'])])
                                .addTo(map);

                            markers.push(marker)

                            if (i==0) {
                                map.flyTo({
                                    center: [parseFloat(res[index]['Longitude']), parseFloat(res[index]['Latitude'])],
                                    zoom: 12,
                                    bearing: 0,                        
                                    speed: 1.8, // make the flying slow
                                    curve: 1, // change the speed at which it zooms out                        
                                    // easing: (t) => t,                        
                                    essential: true
                                })
                            }
                        }
                        

                        i++;
                    })
                    $('#loader').addClass('gone')
                },
                error : function(err) {
                    alert('An error occurred while fetching accounts')
                    $('#loader').addClass('gone')
                }
            })
        }

        // RELOAD MAP ON FEEDRE CHANGE
        $('#District').on('change', function() {
            searchByTown(this.value)
        })

        // // SEARCH POLE
        // $('#searchBtn').on('click', function() {
        //     if (jQuery.isEmptyObject($('#search').val())) {
        //         $('#searchTable tbody tr').remove()
        //     } else {
        //         $.ajax({
        //             url : '/damage_assessments/search-pole',
        //             type : 'GET',
        //             data : {
        //                 Search : $('#search').val(),
        //             }, 
        //             success : function(res) {
        //                 $('#searchTable tbody tr').remove()
        //                 $.each(res, function(index, element) {
        //                     $('#searchTable tbody').append('<tr>' +
        //                         '<td>' + res[index]['ObjectName'] + '</td>' +
        //                         '<td><button id="update" onclick="goToPole(' + res[index]['id'] + ')" class="btn btn-sm btn-link" data-toggle="modal" data-target="#modal-update" pole-no="' + res[index]['ObjectName'] + '" feeder="' + res[index]['Feeder'] + '" status="' + res[index]['Status'] + '" remarks="' + res[index]['Notes'] + '" data-id="' + res[index]['id'] + '" svcid="' + res[index]['id'] + '"><i class="fas fa-eye"></i></button></td>' +
        //                     '</tr>')
        //                 })
        //             },
        //             error : function(err) {
        //                 alert("An error occurred during the search")
        //             }
        //         })
        //     }
            
        // })

        // function goToPole(id) {
        //     $.ajax({
        //         url : '/damage_assessments/view-pole',
        //         type : 'GET',
        //         data : {
        //             id : id
        //         },
        //         success : function(res) {
        //             map.flyTo({
        //                     center: [parseFloat(res['Longitude']), parseFloat(res['Latitude'])],
        //                     zoom: 20,
        //                     bearing: 0,                        
        //                     speed: 1.8, // make the flying slow
        //                     curve: 1, // change the speed at which it zooms out                        
        //                     // easing: (t) => t,                        
        //                     essential: true
        //                 });

        //             if (res['Feeder'] == $('#Feeder').val()) {

        //             } else {
        //                 // CREATE MARKER
        //                 const el = document.createElement('div');
        //                 el.className = 'marker';
        //                 el.id = res['id'];
        //                 el.title = res['ObjectName']
        //                 // el.innerHTML += "<p>" + res['ObjectName'] + "</p>"
                        
        //                 if (res['Status'] == 'OK') {
        //                     el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res['ObjectName'] + '" feeder="' + res['Feeder'] + '" status="' + res['Status'] + '" remarks="' + res['Notes'] + '" data-id="' + res['id'] + '" svcid="' + res['id'] + '" style="margin-left: 10px;"> <span id="' + res['id'] + '"><i class="fas fa-info-circle text-info"></i></span> </button>'
        //                 } else if (res['Status'] == 'ONGOING') {
        //                     el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res['ObjectName'] + '" feeder="' + res['Feeder'] + '" status="' + res['Status'] + '" remarks="' + res['Notes'] + '" data-id="' + res['id'] + '" svcid="' + res['id'] + '" style="margin-left: 10px;"> <span id="' + res['id'] + '"><i class="fas fa-exclamation-triangle text-danger"></i></span> </button>'
        //                 } else if (res['Status'] == 'DONE') {
        //                     el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res['ObjectName'] + '" feeder="' + res['Feeder'] + '" status="' + res['Status'] + '" remarks="' + res['Notes'] + '" data-id="' + res['id'] + '" svcid="' + res['id'] + '" style="margin-left: 10px;"> <span id="' + res['id'] + '"><i class="fas fa-check-circle text-warning"></i></span> </button>'
        //                 }                        
        //                 el.style.width = `15px`;
        //                 el.style.height = `15px`;
        //                 el.style.borderRadius = '50%';
        //                 el.style.backgroundSize = '100%';

        //                 marker = new mapboxgl.Marker(el)
        //                     .setLngLat([parseFloat(res['Longitude']), parseFloat(res['Latitude'])])
        //                     .addTo(map);

        //                 markers.push(marker)
        //             }
                    
        //         },
        //         error : function (err) {
        //             alert("An error occurred during fetching the pole")
        //         }
        //     })
        // }


    </script>
@endpush